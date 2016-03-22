<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Arbac {
	
	/**
	 * The CodeIgniter object variable
	 * @access public
	 * @var object
	 */
	public $CI;

	/**
	 * Variable for loading the config array into
	 * @access public
	 * @var array
	 */
	public $config_vars;

	/**
	 * Array to store error messages
	 * @access public
	 * @var array
	 */
	public $errors = array();

	/**
	 * Array to store info messages
	 * @access public
	 * @var array
	 */
	public $infos = array();
	
	/**
	 * Local temporary storage for current flash errors
	 *
	 * Used to update current flash data list since flash data is only available on the next page refresh
	 * @access public
	 * var array
	 */
	public $flash_errors = array();

	/**
	 * Local temporary storage for current flash infos
	 *
	 * Used to update current flash data list since flash data is only available on the next page refresh
	 * @access public
	 * var array
	 */
	public $flash_infos = array();

	/**
     * The CodeIgniter object variable
	 * @access public
     * @var object
     */
    public $arbac_db;

	########################
	# Base Functions
	########################

	/**
	 * Constructor
	 */
	public function __construct() {

		// get main CI object
		$this->CI = & get_instance();

		// Dependancies
		if(CI_VERSION >= 2.2){
			$this->CI->load->library('driver');
		}
		$this->CI->load->library('session');
		$this->CI->load->library('email');
		$this->CI->load->helper('url');
		$this->CI->load->helper('string');
		$this->CI->load->helper('email');
		$this->CI->load->helper('language');
		$this->CI->load->helper('recaptchalib');
		$this->CI->load->helper('googleauthenticator_helper');
		$this->CI->lang->load('arbac');

	
 		// config/arbac.php
		$this->CI->config->load('arbac');
		$this->config_vars = $this->CI->config->item('arbac');

		$this->arbac_db = $this->CI->load->database($this->config_vars['db_profile'], TRUE); 
		
		// load error and info messages from flashdata (but don't store back in flashdata)
		$this->errors = $this->CI->session->flashdata('errors') ?: array();
		$this->infos = $this->CI->session->flashdata('infos') ?: array();
	}
	
	########################
	# Login Functions
	########################

	//tested
	/**
	 * Login user
	 * Check provided details against the database. Add items to error array on fail, create session if success
	 * @param string $email
	 * @param string $pass
	 * @param bool $remember
	 * @return bool Indicates successful login.
	 */
	public function login($identifier, $pass, $remember = FALSE, $totp_code = NULL) {

		if($this->config_vars['use_cookies'] == TRUE){
			// Remove cookies first
			$cookie = array(
				'name'	 => 'user',
				'value'	 => '',
				'expire' => -3600,
				'path'	 => '/',
			);
			$this->CI->input->set_cookie($cookie);
		}


 		if( $this->config_vars['login_with_name'] == TRUE){

			if( !$identifier OR strlen($pass) < $this->config_vars['min'] OR strlen($pass) > $this->config_vars['max'] )
			{
				$this->error($this->CI->lang->line('arbac_error_login_failed_name'));
				return FALSE;
			}
			$db_identifier = 'name';
 		}else{
			if( !valid_email($identifier) OR strlen($pass) < $this->config_vars['min'] OR strlen($pass) > $this->config_vars['max'] )
			{
				$this->error($this->CI->lang->line('arbac_error_login_failed_email'));
				return FALSE;
			}
			$db_identifier = 'email';
 		}
		/*
		*
		* User Verification
		*
		* Removed or !ctype_alnum($pass) from the IF statement
		* It was causing issues with special characters in passwords
		* and returning FALSE even if the password matches.
		*/

		$query = null;
		$query = $this->arbac_db->where($db_identifier, $identifier);
		$query = $this->arbac_db->get($this->config_vars['users']);
		$row = $query->row();

		// only email found and login attempts exceeded
		if ($query->num_rows() > 0 && $this->config_vars['ddos_protection'] && ! $this->update_login_attempts($row->email)) {

			$this->error($this->CI->lang->line('arbac_error_login_attempts_exceeded'));
			return FALSE;
		}

		//recaptcha login_attempts check
		$query = null;
		$query = $this->arbac_db->where($db_identifier, $identifier);
		$query = $this->arbac_db->get($this->config_vars['users']);
		$row = $query->row();
		if($query->num_rows() > 0 && $this->config_vars['ddos_protection'] && $this->config_vars['recaptcha_active'] && $row->login_attempts >= $this->config_vars['recaptcha_login_attempts']){
			if($this->config_vars['use_cookies'] == TRUE){
				$reCAPTCHA_cookie = array(
					'name'	 => 'reCAPTCHA',
					'value'	 => 'true',
					'expire' => 7200,
					'path'	 => '/',
				);
				$this->CI->input->set_cookie($reCAPTCHA_cookie);
			}else{
				$this->CI->session->set_tempdata('reCAPTCHA', 'true', 7200);
			}
		}

	// if user is not verified
		$query = null;
		$query = $this->arbac_db->where($db_identifier, $identifier);
		$query = $this->arbac_db->where('banned', 1);
		$query = $this->arbac_db->where('verification_code !=', '');
		$query = $this->arbac_db->get($this->config_vars['users']);

		if ($query->num_rows() > 0) {
			$this->error($this->CI->lang->line('arbac_error_account_not_verified'));
			return FALSE;
		}

		// to find user id, create sessions and cookies
		$query = $this->arbac_db->where($db_identifier, $identifier);
		$query = $this->arbac_db->get($this->config_vars['users']);
		
		if($query->num_rows() == 0){
			$this->error($this->CI->lang->line('arbac_error_no_user'));
			return FALSE;
		}
		
		$staff_id = $query->row()->id;
		if($this->config_vars['recaptcha_active']){
			if( ($this->config_vars['use_cookies'] == TRUE && $this->CI->input->cookie('reCAPTCHA', TRUE) == 'true') || ($this->config_vars['use_cookies'] == FALSE && $this->CI->session->tempdata('reCAPTCHA') == 'true') ){
				$reCaptcha = new ReCaptcha( $this->config_vars['recaptcha_secret']);
				$resp = $reCaptcha->verifyResponse( $this->CI->input->server("REMOTE_ADDR"), $this->CI->input->post("g-recaptcha-response") );

				if(!$resp->success){
					$this->error($this->CI->lang->line('arbac_error_recaptcha_not_correct'));
					return FALSE;
				}
			}
		}
	 	
	 	if($this->config_vars['totp_active'] == TRUE AND $this->config_vars['totp_only_on_ip_change'] == FALSE){
			$query = null;
			$query = $this->arbac_db->where($db_identifier, $identifier);
			$query = $this->arbac_db->get($this->config_vars['users']);
			$totp_secret =  $query->row()->totp_secret;
			if ($query->num_rows() > 0 AND !$totp_code) {
				$this->error($this->CI->lang->line('arbac_error_totp_code_required'));
				return FALSE;
			}else {
				if(!empty($totp_secret)){
					$ga = new PHPGangsta_GoogleAuthenticator();
					$checkResult = $ga->verifyCode($totp_secret, $totp_code, 0);
					if (!$checkResult) {
						$this->error($this->CI->lang->line('arbac_error_totp_code_invalid'));
						return FALSE;
					}
				}
			}
	 	}
	 	
	 	if($this->config_vars['totp_active'] == TRUE AND $this->config_vars['totp_only_on_ip_change'] == TRUE){
			$query = null;
			$query = $this->arbac_db->where($db_identifier, $identifier);
			$query = $this->arbac_db->get($this->config_vars['users']);
			$totp_secret =  $query->row()->totp_secret;
			$ip_address = $query->row()->ip_address;
			$current_ip_address = $this->CI->input->ip_address();
			if ($query->num_rows() > 0 AND !$totp_code) {
				if($ip_address != $current_ip_address ){
					$this->error($this->CI->lang->line('arbac_error_totp_code_required'));
					return FALSE;
				}
			}else {
				if(!empty($totp_secret)){
					if($ip_address != $current_ip_address ){
						$ga = new PHPGangsta_GoogleAuthenticator();
						$checkResult = $ga->verifyCode($totp_secret, $totp_code, 0);
						if (!$checkResult) {
							$this->error($this->CI->lang->line('arbac_error_totp_code_invalid'));
							return FALSE;
						}
					}
				}
			}
	 	}
	 	
		$query = null;
		$query = $this->arbac_db->where($db_identifier, $identifier);

		// Database stores pasword hashed password
		$query = $this->arbac_db->where('pass', $this->hash_password($pass, $staff_id));
		$query = $this->arbac_db->where('banned', 0);

		$query = $this->arbac_db->get($this->config_vars['users']);

		$row = $query->row();

		// if email and pass matches and not banned
		if ( $query->num_rows() != 0 ) {

			// If email and pass matches
			// create session
			$data = array(
				'id' => $row->id,
				'name' => $row->name,
				'email' => $row->email,
				'loggedin' => TRUE
			);

			$this->CI->session->set_userdata($data);

			// if remember selected
			if ( $remember ){
				$expire = $this->config_vars['remember'];
				$today = date("Y-m-d");
				$remember_date = date("Y-m-d", strtotime($today . $expire) );
				$random_string = random_string('alnum', 16);
				$this->update_remember($row->id, $random_string, $remember_date );

				if($this->config_vars['use_cookies'] == TRUE){
					$cookie = array(
						'name'	 => 'user',
						'value'	 => $row->id . "-" . $random_string,
						'expire' => 99*999*999,
						'path'	 => '/',
					);

					$this->CI->input->set_cookie($cookie);
				}else{
					$this->CI->session->set_userdata('remember', $row->id . "-" . $random_string);
				}
			}

			if($this->config_vars['recaptcha_active']){
				if($this->config_vars['use_cookies'] == TRUE){
					$reCAPTCHA_cookie = array(
						'name'	 => 'reCAPTCHA',
						'value'	 => 'false',
						'expire' => -3600,
						'path'	 => '/',
					);
					$this->CI->input->set_cookie($reCAPTCHA_cookie);
				}else{
					$this->CI->session->unset_tempdata('reCAPTCHA');
				}
			}
			
			// update last login
			$this->update_last_login($row->id);
			$this->update_activity();
			$this->reset_login_attempts($row->id);
			
			return TRUE;
		}
		// if not matches
		else {

			$this->error($this->CI->lang->line('arbac_error_login_failed_all'));
			return FALSE;
		}
	}
	

	
	
	//tested
	/**
	 * Check user login
	 * Checks if user logged in, also checks remember.
	 * @return bool
	 */
	public function is_loggedin() {

		if ( $this->CI->session->userdata('loggedin') )
		{ return TRUE; }

		// cookie control
		else {
			if($this->config_vars['use_cookies'] == TRUE){
				if( ! $this->CI->input->cookie('user', TRUE) ){
					return FALSE;
				} else {
					$cookie = explode('-', $this->CI->input->cookie('user', TRUE));
					if(!is_numeric( $cookie[0] ) OR strlen($cookie[1]) < 13 ){return FALSE;}
					else{
						$query = $this->arbac_db->where('id', $cookie[0]);
						$query = $this->arbac_db->where('remember_exp', $cookie[1]);
						$query = $this->arbac_db->get($this->config_vars['users']);

						$row = $query->row();

						if ($query->num_rows() < 1) {
							$this->update_remember($cookie[0]);
							return FALSE;
						}else{

							if(strtotime($row->remember_time) > strtotime("now") ){
								$this->login_fast($cookie[0]);
								return TRUE;
							}
							// if time is expired
							else {
								return FALSE;
							}
						}
					}
				}
			}else{
				if(!isset($_SESSION['remember'])){
					return FALSE;
				}else{
					$session = explode('-', $this->CI->session->userdata('remember'));
					if(!is_numeric( $session[0] ) OR strlen($session[1]) < 13 ){return FALSE;}
					else{
						$query = $this->arbac_db->where('id', $session[0]);
						$query = $this->arbac_db->where('remember_exp', $session[1]);
						$query = $this->arbac_db->get($this->config_vars['users']);

						$row = $query->row();

						if ($query->num_rows() < 1) {
							$this->update_remember($session[0]);
							return FALSE;
						}else{

							if(strtotime($row->remember_time) > strtotime("now") ){
								$this->login_fast($session[0]);
								return TRUE;
							}
							// if time is expired
							else {
								return FALSE;
							}
						}
					}
				}

			}
		}
		return FALSE;
	}

	

	//tested
	/**
	 * Logout staff
	 * Destroys the CodeIgniter session and remove cookies to log out staff.
	 * @return bool If session destroy successful
	 */
	public function logout() {

		if($this->config_vars['use_cookies'] == TRUE){
			$cookie = array(
				'name'	 => 'user',
				'value'	 => '',
				'expire' => -3600,
				'path'	 => '/',
			);
			$this->CI->input->set_cookie($cookie);
		}
		
		return $this->CI->session->sess_destroy();
	}

	
	
	
	/**
	 * Reset last login attempts
	 * Sets a staffs 'last login attempts' to null
	 * @param int $staff_id Staff id to reset
	 * @return bool Reset fails/succeeds
	 */
	public	function reset_login_attempts($staff_id) {

		$data['login_attempts'] = null;
		$this->arbac_db->where('id', $staff_id);
		return $this->arbac_db->update($this->config_vars['users'], $data);
	}
	
	

	
	//tested
	/**
	 * Update last login
	 * Update user's last login date
	 * @param int|bool $staff_id User id to update or FALSE for current user
	 * @return bool Update fails/succeeds
	 */
	public function update_last_login($staff_id = FALSE) {

		if ($staff_id == FALSE)
			$staff_id = $this->CI->session->userdata('id');

		$data['last_login'] = date("Y-m-d H:i:s");
		$data['ip_address'] = $this->CI->input->ip_address();

		$this->arbac_db->where('id', $staff_id);
		return $this->arbac_db->update($this->config_vars['users'], $data);
	}


	
	
	//tested
	/**
	 * Update login attempt and if exceeds return FALSE
	 * Update user's last login attemp date and number date
	 * @param string $email User email
	 * @return bool
	 */
	public function update_login_attempts($email) {

		$staff_id = $this->get_staff_id($email);

		$query = $this->arbac_db->where('id', $staff_id);
		$query = $this->arbac_db->get( $this->config_vars['users'] );
		$row = $query->row();


		$data = array();

		if ( strtotime($row->last_login_attempt) == strtotime(date("Y-m-d H:0:0"))) {
			$data['login_attempts'] = $row->login_attempts + 1;

			$query = $this->arbac_db->where('id', $staff_id);
			$this->arbac_db->update($this->config_vars['users'], $data);

		} else {

			$data['last_login_attempt'] = date("Y-m-d H:0:0");
			$data['login_attempts'] = 1;

			$this->arbac_db->where('id', $staff_id);
			$this->arbac_db->update($this->config_vars['users'], $data);

		}

		if ( $data['login_attempts'] > $this->config_vars['max_login_attempt'] ) {
			return FALSE;
		} else {
			return TRUE;
		}

	}
	
	
	//tested
	/**
	 * Update activity
	 * Update staff's last activity date
	 * @param int|bool $staff_id Staff id to update or FALSE for current staff
	 * @return bool Update fails/succeeds
	 */
	public function update_activity($staff_id = FALSE) {

		if ($staff_id == FALSE)
			$staff_id = $this->CI->session->userdata('id');

		if($staff_id==FALSE){return FALSE;}

		$data['last_activity'] = date("Y-m-d H:i:s");

		$query = $this->arbac_db->where('id',$staff_id);
		return $this->arbac_db->update($this->config_vars['users'], $data);
	}
	
	
	########################
	# Staff Functions
	########################

	//tested
	/**
	 * Create staff
	 * Creates a New staff 
	 * @param string $email Staff`'s email address
	 * @param string $pass User's password
	 * @param string $name User's name
	 * @return int|bool False if create fails or returns user id if successful
	 */
	public function create_staff($email, $pass, $username = FALSE, $firstname = FALSE) {

		$valid = TRUE;

		if($this->config_vars['login_with_name'] == TRUE){
			if (empty($name)){
				$this->error($this->CI->lang->line('arbac_error_username_required'));
				$valid = FALSE;
			}
		}
		if ($this->staff_exist_by_username($username) && $username != FALSE) {
			$this->error($this->CI->lang->line('arbac_error_username_exists'));
			$valid = FALSE;
		}

		if ($this->staff_exist_by_email($email)) {
			$this->error($this->CI->lang->line('arbac_error_email_exists'));
			$valid = FALSE;
		}
		$valid_email = (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
		if (!$valid_email){
			$this->error($this->CI->lang->line('arbac_error_email_invalid'));
			$valid = FALSE;
		}
		if ( strlen($pass) < $this->config_vars['min'] OR strlen($pass) > $this->config_vars['max'] ){
			$this->error($this->CI->lang->line('arbac_error_password_invalid'));
			$valid = FALSE;
		}
		
		if ($username != FALSE && !ctype_alnum(str_replace($this->config_vars['valid_chars'], '', $username))){
			$this->error($this->CI->lang->line('arbac_error_username_invalid'));
			$valid = FALSE;
		}
		
		if ($firstname != FALSE && !ctype_alnum(str_replace($this->config_vars['valid_chars'], '', $firstname))){
			$this->error($this->CI->lang->line('arbac_error_firstname_invalid'));
			$valid = FALSE;
		}
		
		if (!$valid) {
			return FALSE; 
		}

		$data = array(
			'email' => $email,
			'pass' => $this->hash_password($pass, 0), // Password cannot be blank but staff_id required for salt, setting bad password for now
			'username' => (!$username) ? '' : $username ,
			'name' => (!$firstname) ? '' : $firstname ,
		);

		if ( $this->arbac_db->insert($this->config_vars['users'], $data )){

			$staff_id = $this->arbac_db->insert_id();

			// set default role
			$this->set_primary_role($staff_id, $this->config_vars['default_role']);

			// if verification activated
			if($this->config_vars['verification']){
				$data = null;
				$data['banned'] = 1;

				$this->arbac_db->where('id', $staff_id);
				$this->arbac_db->update($this->config_vars['users'], $data);

				// sends verifition ( !! e-mail settings must be set)
				$this->send_verification($staff_id);
			}

			// Update to correct salted password
			$data = null;
			$data['pass'] = $this->hash_password($pass, $staff_id);
			$this->arbac_db->where('id', $staff_id);
			$this->arbac_db->update($this->config_vars['users'], $data);

			return $staff_id;

		} else {
			return FALSE;
		}
	}
	
	
	/**
	 * staff_exist_by_name
	 * Check if user exist by name
	 * @param $staff_id
	 *
	 * @return bool
	 */
	public function staff_exist_by_username( $username ) {
		$query = $this->arbac_db->where('username', $username);

		$query = $this->arbac_db->get($this->config_vars['users']);

		if ($query->num_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}

	
	
	/**
	 * staff_exist_by_name
	 * Check if staff exist by name
	 * @param $staff_id
	 *
	 * @return bool
	 */
	public function staff_exist_by_name( $name ) {
		$query = $this->arbac_db->where('name', $name);

		$query = $this->arbac_db->get($this->config_vars['users']);

		if ($query->num_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * staff_exist_by_email
	 * Check if staff exist by staff email
	 * @param $staff_email
	 *
	 * @return bool
	 */
	public function staff_exist_by_email( $staff_email ) {
		$query = $this->arbac_db->where('email', $staff_email);

		$query = $this->arbac_db->get($this->config_vars['users']);

		if ($query->num_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * Get staff id
	 * Get staff id from email address, if par. not given, return current staff's id
	 * @param string|bool $email Email address for staff
	 * @return int Staff id
	 */
	public function get_staff_id($email=FALSE) {

		if( ! $email){
			$query = $this->arbac_db->where('id', $this->CI->session->userdata('id'));
		} else {
			$query = $this->arbac_db->where('email', $email);
		}

		$query = $this->arbac_db->get($this->config_vars['users']);

		if ($query->num_rows() <= 0){
			$this->error($this->CI->lang->line('arbac_error_no_user'));
			return FALSE;
		}
		return $query->row()->id;
	}

	
	
	/**
	 * Hash password
	 * Hash the password for storage in the database
	 * (thanks to Jacob Tomlinson for contribution)
	 * @param string $pass Password to hash
	 * @param $userid
	 * @return string Hashed password
	 */
	function hash_password($pass, $userid) {

		$salt = md5($userid);
		return hash($this->config_vars['hash'], $salt.$pass);
	}
	
	
	########################
	# Role Functions
	########################

	//tested
	/**
	 * Create Role
	 * Creates a new Role
	 * @param string $role_name New Role name
	 * @param string $definition Description of the Role
	 * @return int|bool Role id or FALSE on fail
	 */
	public function create_role($role_name, $definition = '') {

		$query = $this->arbac_db->get_where($this->config_vars['roles'], array('name' => $role_name));

		if ($query->num_rows() < 1) {

			$data = array(
				'name' => $role_name,
				'definition'=> $definition
			);
			$this->arbac_db->insert($this->config_vars['roles'], $data);
			return $this->arbac_db->insert_id();
		}

		$this->info($this->CI->lang->line('arbac_info_role_exists'));
		return FALSE;
	}

	
	
	
	
	
	
	
	
	
	//tested
	/**
	 * Set Primary role
	 * Set Primary role to staff
	 * @param int $staff_id Staff id to add to role
	 * @param int|string $role_par Role id or name assign to staff
	 * @return bool Add success/failure
	 */
	public function set_primary_role($staff_id, $role_par) {

		$role_id = $this->get_role_id($role_par);

		if( ! $role_id ) {

			$this->error( $this->CI->lang->line('arbac_error_no_role') );
			return FALSE;
		}

		$query = $this->arbac_db->where('staff_id',$staff_id);
		$query = $this->arbac_db->where('role_id',$role_id);
		$query = $this->arbac_db->get($this->config_vars['staff_to_role']);

		if ($query->num_rows() < 1) {
			$data = array(
				'staff_id' => $staff_id,
				'role_id' => $role_id
			);

			return $this->arbac_db->insert($this->config_vars['staff'], $data);
		}
		$this->info($this->CI->lang->line('arbac_info_already_member'));
		return TRUE;
	}

	
	//tested
	/**
	 * Get role id
	 * Get role id from role name or id ( ! Case sensitive)
	 * @param int|string $role_par role id or name to get
	 * @return int role id
	 */
	public function get_role_id ( $role_par ) {

		if( is_numeric($role_par) ) { return $role_par; }

		$query = $this->arbac_db->where('name', $role_par);
		$query = $this->arbac_db->get($this->config_vars['roles']);

		if ($query->num_rows() == 0)
			return FALSE;

		$row = $query->row();
		return $row->id;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	########################
	# Error / Info Functions
	########################

	/**
	 * Error
	 * Add message to error array and set flash data
	 * @param string $message Message to add to array
	 * @param boolean $flashdata if TRUE add $message to CI flashdata (deflault: FALSE)
	 */
	public function error($message = '', $flashdata = FALSE){
		
		$this->errors[] = $message;
		
		foreach ($this->errors as $e)
		{
		if($flashdata)
		{
			$this->flash_errors[] = $message;
			$this->CI->session->set_flashdata('errors', $this->flash_errors);
		}	
		} 
		
	}

	/**	
	 * Keep Errors
	 *
	 * Keeps the flashdata errors for one more page refresh.  Optionally adds the default errors into the
	 * flashdata list.  This should be called last in your controller, and with care as it could continue
	 * to revive all errors and not let them expire as intended.
	 * Benefitial when using Ajax Requests
	 * @see http://ellislab.com/codeigniter/user-guide/libraries/sessions.html
	 * @param boolean $include_non_flash TRUE if it should stow basic errors as flashdata (default = FALSE)
	 */
	public function keep_errors($include_non_flash = FALSE)
	{
		// NOTE: keep_flashdata() overwrites anything new that has been added to flashdata so we are manually reviving flash data
		// $this->CI->session->keep_flashdata('errors');

		if($include_non_flash)
		{
			$this->flash_errors = array_merge($this->flash_errors, $this->errors);
		}
		$this->flash_errors = array_merge($this->flash_errors, (array)$this->CI->session->flashdata('errors'));
		$this->CI->session->set_flashdata('errors', $this->flash_errors);
	}

	//tested
	/**
	 * Get Errors Array
	 * Return array of errors
	 * @return array Array of messages, empty array if no errors
	 */
	public function get_errors_array()
	{
		return $this->errors;
	}

	/**
	 * Print Errors
	 * 
	 * Prints string of errors separated by delimiter
	 * @param string $divider Separator for errors
	 */
	public function print_errors($divider = '')
	{
		$msg = '';
		$msg_num = count($this->errors);
		$i = 1;
		foreach ($this->errors as $e)
		{
			$msg .= $e;

			if ($i != $msg_num)
			{
				$msg .= $divider;
			}
			$i++;
		} 
		
		echo $msg;
	}
	
	/**
	 * Clear Errors
	 * 
	 * Removes errors from error list and clears all associated flashdata
	 */
	public function clear_errors()
	{
		$this->errors = array();
		$this->CI->session->set_flashdata('errors', $this->errors);
	}

	/**
	 * Info
	 *
	 * Add message to info array and set flash data
	 * 
	 * @param string $message Message to add to infos array
	 * @param boolean $flashdata if TRUE add $message to CI flashdata (deflault: FALSE)
	 */
	public function info($message = '', $flashdata = FALSE)
	{
		$this->infos[] = $message;
		if($flashdata)
		{
			$this->flash_infos[] = $message;
			$this->CI->session->set_flashdata('infos', $this->flash_infos);
		}
	}

	/**
	 * Keep Infos
	 *
	 * Keeps the flashdata infos for one more page refresh.  Optionally adds the default infos into the
	 * flashdata list.  This should be called last in your controller, and with care as it could continue
	 * to revive all infos and not let them expire as intended.
	 * Benefitial by using Ajax Requests
	 * @see http://ellislab.com/codeigniter/user-guide/libraries/sessions.html
	 * @param boolean $include_non_flash TRUE if it should stow basic infos as flashdata (default = FALSE)
	 */
	public function keep_infos($include_non_flash = FALSE)
	{
		// NOTE: keep_flashdata() overwrites anything new that has been added to flashdata so we are manually reviving flash data
		// $this->CI->session->keep_flashdata('infos');

		if($include_non_flash)
		{
			$this->flash_infos = array_merge($this->flash_infos, $this->infos);
		}
		$this->flash_infos = array_merge($this->flash_infos, (array)$this->CI->session->flashdata('infos'));
		$this->CI->session->set_flashdata('infos', $this->flash_infos);
	}

	/**
	 * Get Info Array
	 *
	 * Return array of infos
	 * @return array Array of messages, empty array if no errors
	 */
	public function get_infos_array()
	{
		return $this->infos;
	}


	/**
	 * Print Info
	 *
	 * Print string of info separated by delimiter
	 * @param string $divider Separator for info
	 *
	 */
	public function print_infos($divider = '<br />')
	{

		$msg = '';
		$msg_num = count($this->infos);
		$i = 1;
		foreach ($this->infos as $e)
		{
			$msg .= $e;

			if ($i != $msg_num)
			{
				$msg .= $divider;
			}
			$i++;
		}
		echo $msg;
	}
	
	/**
	 * Clear Info List
	 * 
	 * Removes info messages from info list and clears all associated flashdata
	 */
	public function clear_infos()
	{
		$this->infos = array();
		$this->CI->session->set_flashdata('infos', $this->infos);
	}
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	