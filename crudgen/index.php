<?php
error_reporting(0);
require_once 'core/crudgen.php';
require_once 'core/helper.php';
require_once 'core/process.php';
?>
<!doctype html>
<html>
    <head>
        <title>Codeigniter CRUD Generator</title>
        <link rel="stylesheet" href="core/bootstrap.min.css"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-md-8">
                <form action="index.php" method="POST">

                    <div class="form-group">
                        <label>Select Table - <a href="<?php echo $_SERVER['PHP_SELF'] ?>">Refresh</a></label>
                        <select id="table_name" name="table_name" class="form-control" onchange="setname()">
                            <option value="">Please Select</option>
                            <?php
                            $table_list = $hc->table_list();
                            $table_list_selected = isset($_POST['table_name']) ? $_POST['table_name'] : '';
                            foreach ($table_list as $table) {
                                ?>
                                <option value="<?php echo $table['table_name'] ?>" <?php echo $table_list_selected == $table['table_name'] ? 'selected="selected"' : ''; ?>><?php echo $table['table_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <?php $jenis_tabel = isset($_POST['jenis_tabel']) ? $_POST['jenis_tabel'] : 'reguler_table'; ?>
                            <div class="col-md-6">
                                <div class="radio" style="margin-bottom: 0px; margin-top: 0px">
                                    <label>
                                        <input type="radio" name="jenis_tabel" value="reguler_table" <?php echo $jenis_tabel == 'reguler_table' ? 'checked' : ''; ?>>
                                        Reguler Table
                                    </label>
                                </div>                            
                            </div>
                            <div class="col-md-6">
                                <div class="radio" style="margin-bottom: 0px; margin-top: 0px">
                                    <label>
                                        <input type="radio" name="jenis_tabel" value="datatables" <?php echo $jenis_tabel == 'datatables' ? 'checked' : ''; ?>>
                                        Datatables
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="form-group" id="table_col">
                        
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <?php $export_excel = isset($_POST['export_excel']) ? $_POST['export_excel'] : ''; ?>
                            <label>
                                <input type="checkbox" name="export_excel" value="1" <?php echo $export_excel == '1' ? 'checked' : '' ?>>
                                Export Excel
                            </label>
                        </div>
                    </div>    

                    <div class="form-group">
                        <div class="checkbox">
                            <?php $export_word = isset($_POST['export_word']) ? $_POST['export_word'] : ''; ?>
                            <label>
                                <input type="checkbox" name="export_word" value="1" <?php echo $export_word == '1' ? 'checked' : '' ?>>
                                Export Word
                            </label>
                        </div>
                    </div>    

                    <!--                    <div class="form-group">
                                            <div class="checkbox  <?php // echo file_exists('../application/third_party/mpdf/mpdf.php') ? '' : 'disabled';   ?>">
                    <?php // $export_pdf = isset($_POST['export_pdf']) ? $_POST['export_pdf'] : ''; ?>
                                                <label>
                                                    <input type="checkbox" name="export_pdf" value="1" <?php // echo $export_pdf == '1' ? 'checked' : ''   ?>
                    <?php // echo file_exists('../application/third_party/mpdf/mpdf.php') ? '' : 'disabled'; ?>>
                                                    Export PDF
                                                </label>
                    <?php // echo file_exists('../application/third_party/mpdf/mpdf.php') ? '' : '<small class="text-danger">mpdf required, download <a href="http://harviacode.com">here</a></small>'; ?>
                                            </div>
                                        </div>-->


                    <div class="form-group">
                        <label>Custom Controller Name</label>
                        <input type="text" id="controller" name="controller" value="<?php echo isset($_POST['controller']) ? $_POST['controller'] : '' ?>" class="form-control" placeholder="Controller Name" />
                    </div>
                    <div class="form-group">
                        <label>Custom Model Name</label>
                        <input type="text" id="model" name="model" value="<?php echo isset($_POST['model']) ? $_POST['model'] : '' ?>" class="form-control" placeholder="Controller Name" />
                    </div>
					
					 <div class="form-group">
                        <label>Custom View Name</label>
                        <input type="text" id="view_name" name="view_name" value="<?php echo isset($_POST['view_name']) ? $_POST['view_name'] : '' ?>" class="form-control" placeholder="View Name" />
                    </div>
					
                    <input type="submit" value="Generate" name="generate" class="btn btn-primary" onclick="javascript: return confirm('This will overwrite the existing files. Continue ?')" />
                    <input type="submit" value="Generate All" name="generateall" class="btn btn-danger" onclick="javascript: return confirm('WARNING !! This will generate code for ALL TABLE and overwrite the existing files\
                    \nPlease double check before continue. Continue ?')" />
                    <a href="core/setting.php" class="btn btn-default">Setting</a>
                </form>
                <br>

                <?php
                foreach ($hasil as $h) {
                    echo '<p>' . $h . '</p>';
                }
                ?>
            </div>
			

			
            <div class="col-md-4">
                <h3 style="margin-top: 0px">Codeigniter CRUD Generator 1.3 by <a target="_blank" href="http://harviacode.com">harviacode.com</a></h3>
                
			</div>	
				

<div class="col-md-4" >
  	<div class="panel panel-default">
  		<div class="panel-heading"><span class="fa fa-cog"></span> Form <span id="selected_table"></span></div>
  		<div class="panel-body" id="proses"></div>
  	</div>
</div>	
				
				<div>
				
				<p><strong>About :</strong></p>
                <p>
                    Codeigniter CRUD Generator is a simple tool to auto generate model, controller and view from your table. This tool will boost your
                    writing code. This CRUD generator will make a complete CRUD operation, pagination, search, form*, form validation, export to excel, and export to word. 
                    This CRUD Generator using bootstrap 3 style. You still need to modify the result code for more customization.
                </p>
                <small>* generate textarea and text input only</small>
                <p>
                    Please visit and like <a target="_blank" href="http://harviacode.com"><b>harviacode.com</b></a> for more info and PHP tutorials.
                </p>
                <p><strong>How to use this CRUD Generator :</strong></p>
                <ul>
                    <li>Simply put 'harviacode' folder, 'asset' folder and .htaccess file into your project root folder.</li>
                    <li>Open http://localhost/yourprojectname/harviacode.</li>
                    <li>Select table and push generate button.</li>
                </ul>
                <p><strong>Important :</strong></p>
                <ul>
                    <li>On application/config/autoload.php, load database library, session library and url helper</li>
                    <li>On application/config/config.php, set :</b>.
                        <ul>
                            <li>$config['base_url'] = 'http://localhost/yourprojectname'</li>
                            <li>$config['index_page'] = ''</li>
                            <li>$config['url_suffix'] = '.html'</li>
                            <li>$config['encryption_key'] = 'randomstring'</li>

                        </ul>

                    </li>
                    <li>On application/config/database.php, set hostname, username, password and database.</li>
                </ul>
                <br>
                <p><strong>Thanks for Support Me</strong></p>
                <p>Buy me a cup of coffee :)</p>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="52D85QFXT57KN">
                        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    </form>
                <br>
                <p><strong>Update</strong></p>

                <ul>
                    <li>V.1.3 - 09 December 2015
                        <ul>
                            <li>Zero Config for database connection</li>
                            <li>Fix bug searching</li>
                            <li>Fix field name label</li>
                            <li>Add select table from database</li>
                            <li>Add generate all table</li>
                            <li>Select target folder from setting menu</li>
                            <li>Remove support for Codeigniter 2</li>
                        </ul>
                    </li>
                    <li>V.1.2 - 25 June 2015
                        <ul>
                            <li>Add custom target folder</li>
                            <li>Add export to excel</li>
                            <li>Add export to word</li>
                        </ul>
                    </li>
                    <li>V.1.1 - 21 May 2015
                        <ul>
                            <li>Add custom controller name and custom model name</li>
                            <li>Add client side datatables</li>
                        </ul>
                    </li>
                </ul>

                <p><strong>&COPY; 2015 <a target="_blank" href="http://harviacode.com">harviacode.com</a></strong></p>
            </div>
        </div>
        <script type="text/javascript">
            function capitalize(s) {
                return s && s[0].toUpperCase() + s.slice(1);
            }

            function setname() {
                var table_name = document.getElementById('table_name').value.toLowerCase();
                if (table_name != '') {
                    document.getElementById('controller').value = capitalize(table_name);
					document.getElementById('model').value = capitalize(table_name) + '_model';
					document.getElementById('view_name').value = table_name;
					document.getElementById("selected_table").innerHTML = " - " + table_name;
					
                } else {
                    document.getElementById('controller').value = '';
                    document.getElementById('model').value = '';
                }
				
				show_table_col(table_name);	
				
            }
			
			function show_table_col(table_name) {
				document.getElementById("table_col").innerHTML = "";
				if (table_name.length == 0) {
					document.getElementById("table_col").innerHTML = "";
					return;
				} else {
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							//arr_col[];
							if(xmlhttp.responseText){
								var arr_col = JSON.parse(xmlhttp.responseText);
								  
										tableCreate(arr_col);
									
							}
							//document.getElementById("table_col").innerHTML = xmlhttp.responseText;
						}
					};
					xmlhttp.open("GET", "core/ajax_call.php?q=" + table_name, true);
					xmlhttp.send();
				}
			}
			function tableCreate(arr_col) {
				
				
				var body = document.getElementById("table_col");
				var tbl = document.createElement('table');
				tbl.style.width = '100%';
				tbl.setAttribute('border', '1');
				var tbdy = document.createElement('tbody');
				 for(i=0;i<arr_col.length;i++){
					var tr = document.createElement('tr');
				 if(arr_col[i].COLUMN_KEY!='PRI' && arr_col[i].COLUMN_NAME!='created' && arr_col[i].COLUMN_NAME!='updated'){
					for (var j = 0; j < 5; j++) {
						if(j==0){
							var td = document.createElement('td');
							td.innerHTML = arr_col[i].COLUMN_NAME;
							tr.appendChild(td); 
						}
						if(j==1){
							var td = document.createElement('td');
							
							var html_str = document.createElement("span");
							html_str.innerHTML = arr_col[i].COLUMN_TYPE;
							
							var box_type = document.createElement("select");
							box_type.name = "input_box_"+arr_col[i].COLUMN_NAME;
							box_type.id = "input_box_"+arr_col[i].COLUMN_NAME;
							td.appendChild(html_str);
							td.appendChild(box_type);
							var arr_sel = ["textbox","textarea","checkbox","radio","dropdown","password","datepicker"];
							//Create and append the options
							var option = document.createElement("option");
								option.value = '';
								option.text = 'select';
								box_type.appendChild(option);
							for (var k = 0; k < arr_sel.length; k++) {
								var option = document.createElement("option");
								option.value = arr_sel[k];
								option.text = arr_sel[k];
								if(arr_sel[k] == "required"){
									option.selected = "selected";
								}
								box_type.appendChild(option);
							}
							
							tr.appendChild(td); 
						}
						if(j==2){
							var td = document.createElement('td');
							var checkbox = document.createElement('input');
							checkbox.type = "checkbox";
							checkbox.name = "chk_not_required[]";
							checkbox.value = arr_col[i].COLUMN_NAME;
							checkbox.id = arr_col[i].COLUMN_NAME;
							//checkbox.onclick ="select_not_required("+arr_col[i].COLUMN_NAME+")";
							var label = document.createElement('label')
							label.htmlFor = arr_col[i].COLUMN_NAME;
							label.appendChild(document.createTextNode('ignore field'));
							checkbox.setAttribute("onclick", "select_not_required('"+arr_col[i].COLUMN_NAME+"')");
							td.appendChild(checkbox);
							td.appendChild(label);
							
							//td.appendChild = (checkbox);
							tr.appendChild(td);
							
							
						}
						if(j==3){
							var td = document.createElement('td');
							var checkbox = document.createElement('input');
							checkbox.type = "checkbox";
							checkbox.name = "chk_not_req_validation[]";
							checkbox.value = arr_col[i].COLUMN_NAME;
							checkbox.id = 'no_vali'+arr_col[i].COLUMN_NAME;
							var label = document.createElement('label')
							label.htmlFor = arr_col[i].COLUMN_NAME;
							label.appendChild(document.createTextNode('ignore validation'));
							checkbox.setAttribute("onclick", "select_not_required_validation('"+arr_col[i].COLUMN_NAME+"')");
							td.appendChild(checkbox);
							td.appendChild(label);
							
							//td.appendChild = (checkbox);
							tr.appendChild(td);
							
						}
						if(j==4){
							var td = document.createElement('td');
							
							//Create array of options to be added
							var arr_sel = ["required","email","is_unique","date","url","number","digits"];

							//Create and append select list
							var selectList = document.createElement("select");
							selectList.name = "sel_validation_"+arr_col[i].COLUMN_NAME+"[]";
							selectList.id = "sel_validation_"+arr_col[i].COLUMN_NAME;
							selectList.multiple = "multiple";
							//selectList.setAttribute("onchange", "select_validation_change('"+arr_col[i].COLUMN_NAME+"')");
							td.appendChild(selectList);

							//Create and append the options
							for (var k = 0; k < arr_sel.length; k++) {
								var option = document.createElement("option");
								option.value = arr_sel[k];
								option.text = arr_sel[k];
								if(arr_sel[k] == "required"){
									option.selected = "selected";
								}
								selectList.appendChild(option);
							}

							//td.appendChild(checkbox);
							//td.appendChild(label);
							
							//td.appendChild = (checkbox);
							tr.appendChild(td);
							
						}
						
					}
				 }
					tbdy.appendChild(tr);
				}
				tbl.appendChild(tbdy);
				body.appendChild(tbl)
				
				//document.getElementById("table_col").innerHTML = y;
			}
			
			function select_not_required_validation(val){
				inputElement = document.getElementById('no_vali'+val);
				if(inputElement.checked){
							 disable('sel_validation_'+val);
						  }
						  else{
							 enable('sel_validation_'+val);
						  }
			}
		    function select_not_required(val){
				inputElement = document.getElementById(val);
				if(inputElement.checked){
							 disable('no_vali'+val);
							 disable('sel_validation_'+val);
						  }
						  else{
							 enable('no_vali'+val);
							 enable('sel_validation_'+val);
						  }
			}
			function enable(id) {
				var x = document.getElementById(id);
				x.disabled = false;
			}

			function disable(id) {
				var x = document.getElementById(id);
				x.checked = false;
				
				x.selectedIndex = -1;
				x.setAttribute("disabled", "true");
			}
		
	
        </script>
    
	
	</body>
	
	
</html>
