<?php

$string = "<?php

/* Location: ./application/controllers/$c_file */
/* Derived from Harviacode  */
/* Modified by Vivek Raghunathan ".date('Y-m-d H:i:s')." */
/* Email : vivekra@dqserv.com */
/* http://dqserv.com */



if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class " . $c . " extends APP_Controller
{
    function __construct()
    {
        parent::__construct();
        \$this->load->model('$m');
        \$this->load->library('form_validation');
		\$this->load->library('Arbac');
		if(!\$this->arbac->is_loggedin()){ redirect('scp/login'); }
    }";

if ($jenis_tabel == 'reguler_table') {
    
$string .= "\n\n    public function index()
    {
        \$data = array(
            'title' => 'TRAMS::SCP::".$table_name."',
            'sort_by' => 'DESC',
            'sort_column' => '".$pk."',
			'q' => '',
			'total_rows' => ''
        );
        \$this->_tpl('$c_url/$v_list', \$data);
    }";
$string .= "\n\n    public function list_data()
    {
		\$page = \$this->input->post('page');
        \$sort_by = \$this->input->post('sortby');
        \$sort_column = \$this->input->post('sort_column');
		\$q = \$this->input->post('q');

        if (!\$page) {
            \$offset = 0;
        } else {
            \$offset = \$page;
        }

        \$this->perPage = 5;

        if (\$this->input->post('per_page') > 0) {
            \$this->perPage = \$this->input->post('per_page');
        }

        //pagination configuration
        \$config['first_link'] = 'First';
        \$config['div'] = '".$c."_list'; //parent div tag id
        \$config['base_url'] = base_url(). '".$c."/list_data';
        \$config['total_rows'] = \$this->" . $m . "->total_rows(\$q);
        \$config['per_page'] = \$this->perPage;

        \$this->ajax_pagination->initialize(\$config);

        //get the posts data
        \$".$c_url."_data = \$this->" . $m . "->get_limit_data(\$config['per_page'], \$offset,\$sort_column ,\$sort_by , \$q);

        \$data = array(
            '" . $c_url . "_data' => \$".$c_url."_data,
            'page' => \$page,
            'sort_by' => \$sort_by,
            'sort_column' => \$sort_column,
            'set_user_pagination_status' => \$this->perPage,
            'title' => 'TRAMS | ',
            'primary_role_id' => \$this->session->userdata('primary_role'),
			'total_rows' => \$config['total_rows']
        );

        //load the view
        \$this->load->view('$c_url/$v_list_data', \$data);
    }";
} else {
    
$string .="\n\n    public function index()
    {
        \$$c_url = \$this->" . $m . "->get_all();

        \$data = array(
            '" . $c_url . "_data' => \$$c_url,
			'title' => 'TRAMS::SCP::$c',
        );
		\$this->_tpl('$c_url/$v_list', \$data);
		
    }";

}
    
$string .= "\n\n    public function read(\$id) 
    {
        \$row = \$this->" . $m . "->get_by_id(\$id);
        if (\$row) {
            \$data = array(
			'title'  => 'TRAMS::SCP::$c',";
foreach ($all as $row) {
	if($_POST['input_box_'.$row["column_name"]] == 'datepicker'){
		$string .= "\n\t\t'" . $row['column_name'] . "' => date(\"m/d/Y\", strtotime(\$row->" . $row['column_name'] . ")),";
	}
	else {
		$string .= "\n\t\t'" . $row['column_name'] . "' => \$row->" . $row['column_name'] . ",";
	}
}
$string .= "\n\t    );
			\$this->_tpl('$c_url/$v_read', \$data);
        } else {
            \$this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('$c_url'));
        }
    }

    public function create() 
    {
        \$data = array(
            'button' => 'Create',
            'action' => site_url('$c_url/create_action'),
			'title'  => 'TRAMS::SCP::Create $c',";
foreach ($all as $row) {
	if(!in_array($row["column_name"], $col_not_req)){
			$string .= "\n\t    '" . $row['column_name'] . "' => set_value('" . $row['column_name'] . "'),";
	}
}
$string .= "\n\t);
          \$this->_tpl('$c_url/$v_form', \$data);	
		
    }
    
    public function create_action() 
    {
        \$this->_rules();

        if (\$this->form_validation->run() == FALSE) {
            \$this->create();
        } else {
            \$data = array(";
foreach ($non_pk as $row) {
	
		if ($row['column_name'] == 'created' || $row['column_name'] == 'updated') {
				$string .= "\n\t\t'" . $row['column_name'] . "' => date('Y-m-d H:i:s'),";
		}
		else{
			if(!in_array($row["column_name"], $col_not_req)){
				if($_POST['input_box_'.$row["column_name"]] == 'datepicker'){
					$string .= "\n\t\t'" . $row['column_name'] . "' => date('Y-m-d', strtotime(str_replace('-', '/', \$this->input->post('" . $row['column_name'] . "',TRUE)))),";
				}
				else {
					$string .= "\n\t\t'" . $row['column_name'] . "' => \$this->input->post('" . $row['column_name'] . "',TRUE),";
				}
			}
    
		}
}
$string .= "\n\t    );

            \$this->".$m."->insert(\$data);
            \$this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('$c_url'));
        }
    }
    
    public function update(\$id) 
    {
        \$row = \$this->".$m."->get_by_id(\$id);

        if (\$row) {
            \$data = array(
                'button' => 'Update',
                'action' => site_url('$c_url/update_action'),
				'title'  => 'TRAMS::SCP::Update $c',";
				
foreach ($all as $row) {
	if($_POST['input_box_'.$row["column_name"]] == 'datepicker'){
		$string .= "\n\t\t'" . $row['column_name'] . "' => set_value('" . $row['column_name'] . "', date(\"m/d/Y\", strtotime(\$row->". $row['column_name']."))),";
	}
	else {
		$string .= "\n\t\t'" . $row['column_name'] . "' => set_value('" . $row['column_name'] . "', \$row->". $row['column_name']."),";
	}
}
$string .= "\n\t    );
			\$this->_tpl('$c_url/$v_edit', \$data);
		
        } else {
            \$this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('$c_url'));
        }
    }
    
    public function update_action() 
    {
        \$this->_rules();

        if (\$this->form_validation->run() == FALSE) {
            \$this->update(\$this->input->post('$pk', TRUE));
        } else {
            \$data = array(";
			
foreach ($non_pk as $row) {
	
	
	if ($row['column_name'] == 'created') {
				$string .= "\n\t\t";
		}
	
	
	elseif ($row['column_name'] == 'updated') {
				$string .= "\n\t\t'" . $row['column_name'] . "' => date('Y-m-d H:i:s'),";
		}
		else{
			if(!in_array($row["column_name"], $col_not_req)){
				if(!in_array($row["column_name"], $col_not_req)){
					if($_POST['input_box_'.$row["column_name"]] == 'datepicker'){
						$string .= "\n\t\t'" . $row['column_name'] . "' => date('Y-m-d', strtotime(str_replace('-', '/', \$this->input->post('" . $row['column_name'] . "',TRUE)))),";
					}
					else {
						$string .= "\n\t\t'" . $row['column_name'] . "' => \$this->input->post('" . $row['column_name'] . "',TRUE),";
					}
				}
		    }
		}
		
	
	
	
    //$string .= "\n\t\t'" . $row['column_name'] . "' => \$this->input->post('" . $row['column_name'] . "',TRUE),";
}    
$string .= "\n\t    );

            \$this->".$m."->update(\$this->input->post('$pk', TRUE), \$data);
            \$this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('$c_url'));
        }
    }
    
    public function delete(\$id) 
    {
        \$row = \$this->".$m."->get_by_id(\$id);

        if (\$row) {
            \$this->".$m."->delete(\$id);
            \$this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('$c_url'));
        } else {
            \$this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('$c_url'));
        }
    }

    public function _rules() 
    {";
foreach ($non_pk as $row) {
    $int = $row['data_type'] == 'int' || $row['data_type'] == 'double' || $row['data_type'] == 'decimal' ? '|numeric' : '';
	$required = '';
	
		if ($row['column_name'] == 'created' || $row['column_name'] == 'updated') {
				$string .= "\n\t\t";
		}
		else{
			if(!in_array($row["column_name"], $col_not_req)){
				if(!in_array($row["column_name"], $col_not_req_validation)){
					if(count($_POST['sel_validation_'.$row["column_name"]])){
						if(in_array('required', $_POST['sel_validation_'.$row["column_name"]])){
								$required = "required";
							}
						$string .= "\n\t\$this->form_validation->set_rules('".$row['column_name']."', '".  strtolower(label($row['column_name']))."', 'trim|$required');";
					}
					else {
					$string .= "\n\t\$this->form_validation->set_rules('".$row['column_name']."', '".  strtolower(label($row['column_name']))."', 'trim|required$int');";
					}
				}
			}
		    
		}	
}    
$string .= "\n\n\t\$this->form_validation->set_rules('$pk', '$pk', 'trim');";
$string .= "\n\t\$this->form_validation->set_error_delimiters('<span class=\"text-danger\">', '</span>');
    }";

if ($export_excel == '1') {
    $string .= "\n\n    public function excel()
    {
        \$this->load->helper('exportexcel');
        \$namaFile = \"$table_name.xls\";
        \$judul = \"$table_name\";
        \$tablehead = 0;
        \$tablebody = 1;
        \$nourut = 1;
        //penulisan header
        header(\"Pragma: public\");
        header(\"Expires: 0\");
        header(\"Cache-Control: must-revalidate, post-check=0,pre-check=0\");
        header(\"Content-Type: application/force-download\");
        header(\"Content-Type: application/octet-stream\");
        header(\"Content-Type: application/download\");
        header(\"Content-Disposition: attachment;filename=\" . \$namaFile . \"\");
        header(\"Content-Transfer-Encoding: binary \");

        xlsBOF();

        \$kolomhead = 0;
        xlsWriteLabel(\$tablehead, \$kolomhead++, \"No\");";
foreach ($non_pk as $row) {
        $column_name = label($row['column_name']);
        $string .= "\n\txlsWriteLabel(\$tablehead, \$kolomhead++, \"$column_name\");";
}
$string .= "\n\n\tforeach (\$this->" . $m . "->get_all() as \$data) {
            \$kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber(\$tablebody, \$kolombody++, \$nourut);";
foreach ($non_pk as $row) {
        $column_name = $row['column_name'];
        $xlsWrite = $row['data_type'] == 'int' || $row['data_type'] == 'double' || $row['data_type'] == 'decimal' ? 'xlsWriteNumber' : 'xlsWriteLabel';
        $string .= "\n\t    " . $xlsWrite . "(\$tablebody, \$kolombody++, \$data->$column_name);";
}
$string .= "\n\n\t    \$tablebody++;
            \$nourut++;
        }

        xlsEOF();
        exit();
    }";
}

if ($export_word == '1') {
    $string .= "\n\n    public function word()
    {
        header(\"Content-type: application/vnd.ms-word\");
        header(\"Content-Disposition: attachment;Filename=$table_name.doc\");

        \$data = array(
            '" . $table_name . "_data' => \$this->" . $m . "->get_all(),
            'start' => 0
        );
        
        \$this->load->view('" . $v_doc . "',\$data);
    }";
}

if ($export_pdf == '1') {
    $string .= "\n\n    function pdf()
    {
        \$data = array(
            '" . $table_name . "_data' => \$this->" . $m . "->get_all(),
            'start' => 0
        );
        
        ini_set('memory_limit', '32M');
        \$html = \$this->load->view('" . $v_pdf . "', \$data, true);
        \$this->load->library('pdf');
        \$pdf = \$this->pdf->load();
        \$pdf->WriteHTML(\$html);
        \$pdf->Output('" . $table_name . ".pdf', 'D'); 
    }";
}

$string .= "\n\n    public function check_exist()
    {
    \$val = \$this->input->post('val');
	\$col = \$this->input->post('col');
	\$id = isset(\$_POST['id']) ? \$_POST['id'] : '';
	\$res = \$this->" . $m . "->check_exist(\$val,\$col,\$id);
	if(\$res){
		echo 'false';
	}
	else {
		echo 'true';
	}
    }";
$string .= "\n\n}\n\n/* End of file $c_file */
";




$hasil_controller = createFile($string, $target . "controllers/" . $c_file);

?>