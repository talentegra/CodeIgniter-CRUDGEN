<?php

/* Location: ./application/controllers/Customer.php */
/* Derived from Harviacode  */
/* Modified by Vivek Raghunathan 2016-03-29 23:31:32 */
/* Email : vivekra@dqserv.com */
/* http://dqserv.com */



if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->library('form_validation');
		$this->load->library('Arbac');
		if(!$this->arbac->is_loggedin()){ redirect('scp/login'); }
    }

    public function index()
    {
        $customer = $this->Customer_model->get_all();

        $data = array(
            'customer_data' => $customer
        );
		$this->load->view('templates/header', $data); 	
        $this->load->view('customer_list', $data);
		$this->load->view('templates/footer', $data); 	
		
    }

    public function read($id) 
    {
        $row = $this->Customer_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'name' => $row->name,
		'cust_type' => $row->cust_type,
		'manager' => $row->manager,
		'manager_mobile' => $row->manager_mobile,
		'status' => $row->status,
		'domain' => $row->domain,
		'extra' => $row->extra,
		'created' => $row->created,
		'updated' => $row->updated,
	    );
			$this->load->view('templates/header', $data); 
            $this->load->view('customer_read', $data);
			
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('customer'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('customer/create_action'),
	    'id' => set_value('id'),
	    'name' => set_value('name'),
	    'cust_type' => set_value('cust_type'),
	    'manager' => set_value('manager'),
	    'manager_mobile' => set_value('manager_mobile'),
	    'status' => set_value('status'),
	    'domain' => set_value('domain'),
	    'extra' => set_value('extra'),
	    'created' => set_value('created'),
	    'updated' => set_value('updated'),
	);
        $this->load->view('templates/header', $data); 	
        $this->load->view('customer_form', $data);
		$this->load->view('templates/footer', $data); 	
		
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'name' => $this->input->post('name',TRUE),
		'cust_type' => $this->input->post('cust_type',TRUE),
		'manager' => $this->input->post('manager',TRUE),
		'manager_mobile' => $this->input->post('manager_mobile',TRUE),
		'status' => $this->input->post('status',TRUE),
		'domain' => $this->input->post('domain',TRUE),
		'extra' => $this->input->post('extra',TRUE),
		'created' => date('Y-m-d H:i:s'),
		'updated' => date('Y-m-d H:i:s'),
	    );

            $this->Customer_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('customer'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Customer_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('customer/update_action'),
		'id' => set_value('id', $row->id),
		'name' => set_value('name', $row->name),
		'cust_type' => set_value('cust_type', $row->cust_type),
		'manager' => set_value('manager', $row->manager),
		'manager_mobile' => set_value('manager_mobile', $row->manager_mobile),
		'status' => set_value('status', $row->status),
		'domain' => set_value('domain', $row->domain),
		'extra' => set_value('extra', $row->extra),
		'created' => set_value('created', $row->created),
		'updated' => set_value('updated', $row->updated),
	    );
			$this->load->view('templates/header', $data); 
            $this->load->view('customer_form', $data);
			$this->load->view('templates/footer', $data);
		
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('customer'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'name' => $this->input->post('name',TRUE),
		'cust_type' => $this->input->post('cust_type',TRUE),
		'manager' => $this->input->post('manager',TRUE),
		'manager_mobile' => $this->input->post('manager_mobile',TRUE),
		'status' => $this->input->post('status',TRUE),
		'domain' => $this->input->post('domain',TRUE),
		'extra' => $this->input->post('extra',TRUE),
		
		'updated' => date('Y-m-d H:i:s'),
	    );

            $this->Customer_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('customer'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Customer_model->get_by_id($id);

        if ($row) {
            $this->Customer_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('customer'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('customer'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('name', 'name', 'trim|required');
	$this->form_validation->set_rules('cust_type', 'cust type', 'trim|required');
	$this->form_validation->set_rules('manager', 'manager', 'trim|required');
	$this->form_validation->set_rules('manager_mobile', 'manager mobile', 'trim|required');
	$this->form_validation->set_rules('status', 'status', 'trim|required|numeric');
	$this->form_validation->set_rules('domain', 'domain', 'trim|required');
	$this->form_validation->set_rules('extra', 'extra', 'trim|required');
		
		

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Customer.php */
