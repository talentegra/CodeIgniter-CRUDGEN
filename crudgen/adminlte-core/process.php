<?php

$hasil = array();

if (isset($_POST['generate']))
{
    // get form data
    $table_name = safe($_POST['table_name']);
    $jenis_tabel = safe($_POST['jenis_tabel']);
    $export_excel = safe($_POST['export_excel']);
    $export_word = safe($_POST['export_word']);
    $export_pdf = safe($_POST['export_pdf']);
    $controller = safe($_POST['controller']);
	$view_name = safe($_POST['view_name']);
    $model = safe($_POST['model']);
	
	$col_not_req = array();
	$col_not_req_validation = array();
	//$chk_not_required = safe($_POST['chk_not_required[]']);
	if( isset($_POST['chk_not_required']) && is_array($_POST['chk_not_required']) ) {
    foreach($_POST['chk_not_required'] as $not_required) {
        $col_not_req[] = $not_required;
    }
	}
	if( isset($_POST['chk_not_req_validation']) && is_array($_POST['chk_not_req_validation']) ) {
    foreach($_POST['chk_not_req_validation'] as $not_required) {
        $col_not_req_validation[] = $not_required;
    }
	}
	
	//$model = safe($_POST['model']);
	//$model = safe($_POST['model']);
	
    if ($table_name <> '')
    {
        // set data
        $table_name = $table_name;
        $c = $controller <> '' ? ucfirst($controller) : ucfirst($table_name);
        $m = $model <> '' ? ucfirst($model) : ucfirst($table_name) . '_model';
        $v_list = $view_name <> '' ? $view_name."_list" : $table_name . "_list"; 
        $v_read = $view_name <> '' ? $view_name."_read" : $table_name . "_read"; 
        $v_form = $view_name <> '' ? $view_name."_form" : $table_name . "_form"; 
		$v_edit = $view_name <> '' ? $view_name."_edit" : $table_name . "_edit";
        $v_doc = $table_name . "_doc";
        $v_pdf = $table_name . "_pdf";
		$v_list_data = $view_name <> '' ? $view_name."_list_data" : $table_name . "_list_data"; 
        
        // url
        $c_url = strtolower($c);
        
        // filename
        $c_file = $c.'.php';
        $m_file = $m.'.php';
        $v_list_file = $v_list.'.php';
        $v_read_file = $v_read.'.php';
        $v_form_file = $v_form.'.php';
		$v_edit_file = $v_edit.'.php';
        $v_doc_file = $v_doc.'.php';
        $v_pdf_file = $v_pdf.'.php';
		$v_list_data_file = $v_list_data . '.php';
        
        // read setting
        $get_setting = readJSON('core/settingjson.cfg');
        $target = $get_setting->target;
        
        $pk = $hc->primary_field($table_name);
        $non_pk = $hc->not_primary_field($table_name);
        $all = $hc->all_field($table_name);
        $view_fields = $hc->display_field($table_name);
        // generate
        include 'core/create_config_pagination.php';
        include 'core/create_controller.php';
        include 'core/create_model.php';
        $jenis_tabel == 'reguler_table' ? include 'core/create_view_list.php' : include 'core/create_view_list_datatables.php';  
		$jenis_tabel == 'reguler_table' ? include 'core/create_view_list_data.php' : '';  		
        include 'core/create_view_form.php';
		include 'core/create_view_edit.php';
        include 'core/create_view_read.php';
        
        $export_excel == 1 ? include 'core/create_exportexcel_helper.php' : '';
        $export_word == 1 ? include 'core/create_view_list_doc.php' : '';
        $export_pdf == 1 ? include 'core/create_pdf_library.php' : '';
        $export_pdf == 1 ? include 'core/create_view_list_pdf.php' : '';
        
        $hasil[] = $hasil_controller;
        $hasil[] = $hasil_model;
        $hasil[] = $hasil_view_list;
        $hasil[] = $hasil_view_form;
		$hasil[] = $hasil_view_edit;
        $hasil[] = $hasil_view_read;
        $hasil[] = $hasil_view_doc;
        $hasil[] = $hasil_view_pdf;
        $hasil[] = $hasil_config_pagination;
        $hasil[] = $hasil_exportexcel;
        $hasil[] = $hasil_pdf;

    }
    else
    {
        $hasil[] = 'No table selected.';
    }
} 

if (isset($_POST['generateall'])) {
    
    $jenis_tabel = safe($_POST['jenis_tabel']);
    $export_excel = safe($_POST['export_excel']);
    $export_word = safe($_POST['export_word']);
    $export_pdf = safe($_POST['export_pdf']);

    $table_list = $hc->table_list();
    foreach ($table_list as $row) {
        
        $table_name = $row['table_name'];
        $c = ucfirst($table_name);
        $m = ucfirst($table_name) . '_model';
        $v_list = $table_name . "_list";
        $v_read = $table_name . "_read";
        $v_form = $table_name . "_form";
        $v_doc = $table_name . "_doc";
        $v_pdf = $table_name . "_pdf";
        
        // url
        $c_url = strtolower($c);
        
        // filename
        $c_file = $c.'.php';
        $m_file = $m.'.php';
        $v_list_file = $v_list.'.php';
        $v_read_file = $v_read.'.php';
        $v_form_file = $v_form.'.php';
		$v_edit_file = $v_edit.'.php';
        $v_doc_file = $v_doc.'.php';
        $v_pdf_file = $v_pdf.'.php';
        
        // read setting
        $get_setting = readJSON('core/settingjson.cfg');
        $target = $get_setting->target;
        
        $pk = $hc->primary_field($table_name);
        $non_pk = $hc->not_primary_field($table_name);
        $all = $hc->all_field($table_name);
        
        // generate
        include 'core/create_config_pagination.php';
        include 'core/create_controller.php';
        include 'core/create_model.php';
        $jenis_tabel == 'reguler_table' ? include 'core/create_view_list.php' : include 'core/create_view_list_datatables.php';       
        include 'core/create_view_form.php';
		include 'core/create_view_edit.php';
        include 'core/create_view_read.php';
        
        $export_excel == 1 ? include 'core/create_exportexcel_helper.php' : '';
        $export_word == 1 ? include 'core/create_view_list_doc.php' : '';
        $export_pdf == 1 ? include 'core/create_pdf_library.php' : '';
        $export_pdf == 1 ? include 'core/create_view_list_pdf.php' : '';
        
        $hasil[] = $hasil_controller;
        $hasil[] = $hasil_model;
        $hasil[] = $hasil_view_list;
        $hasil[] = $hasil_view_form;
		$hasil[] = $hasil_view_edit;
        $hasil[] = $hasil_view_read;
        $hasil[] = $hasil_view_doc;

    }
    
    $hasil[] = $hasil_config_pagination;
    $hasil[] = $hasil_exportexcel;
    $hasil[] = $hasil_pdf;
}
?>