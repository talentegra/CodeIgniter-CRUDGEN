<?php 

$string = "<div role=\"main\" class=\"right_col\" style=\"min-height: 3687px;\">
    <div class=\"\">
        <div class=\"page-title\">
            <div class=\"title_left\">
                <h3>Create " .ucfirst($table_name). "</h3>
            </div>

        </div>

	<div class=\"clearfix\"></div>
        <div class=\"row\">
            <div class=\"col-md-12 col-sm-12 col-xs-12\">
                <div class=\"x_panel\">

                    <div class=\"x_content\">
                        <br>
<form id=\"frm_create\" name=\"frm_create\" class=\"form-horizontal form-label-left\" data-parsley-validate=\"\" action=\"<?php echo \$action; ?>\" method=\"post\">";
$datepicker_type = '';		
foreach ($non_pk as $row) {
	if(!in_array($row["column_name"], $col_not_req)){
	if($_POST['input_box_'.$row["column_name"]]){
	$input_type = $_POST['input_box_'.$row["column_name"]];

		switch ($input_type) {
			case "textbox":
				$string .= "\n\t    <div class=\" form-group\">
					 <label class=\"control-label col-md-3 col-sm-3 col-xs-12\" for=\"".$row["data_type"]."\">".label($row["column_name"])."</label>
					<div class=\"col-md-6 col-sm-6 col-xs-12\">
					<input type=\"text\" class=\"form-control col-md-7 col-xs-12\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
					<?php echo form_error('".$row["column_name"]."') ?>
				</div>
				</div>";
				break;
			case "textarea":
				$string .= "\n\t    <div class=\" form-group\"> 
					<label class=\"control-label col-md-3 col-sm-3 col-xs-12\" for=\"".$row["column_name"]."\">".label($row["column_name"])."</label>
					<div class=\"col-md-6 col-sm-6 col-xs-12\">
					<textarea class=\"form-control col-md-7 col-xs-12\" rows=\"3\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\"><?php echo $".$row["column_name"]."; ?></textarea>
					<?php echo form_error('".$row["column_name"]."') ?>
				</div>
				</div>";
				break;
			case "checkbox":
				$string .= "\n\t    <div class=\" form-group\">
					 <label class=\"control-label col-md-3 col-sm-3 col-xs-12\" for=\"".$row["data_type"]."\">".label($row["column_name"])."</label>
					<div class=\"col-md-6 col-sm-6 col-xs-12\">
					<input type=\"checkbox\" style=\"width:1%\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"1\" />
					<?php echo form_error('".$row["column_name"]."') ?>
				</div>
				</div>";
				break;
			case "radio":
				$string .= "\n\t    <div class=\" form-group\">
					 <label class=\"control-label col-md-3 col-sm-3 col-xs-12\" for=\"".$row["data_type"]."\">".label($row["column_name"])."</label>
					<div class=\"col-md-6 col-sm-6 col-xs-12\">
					<label><input type=\"radio\" style=\"width:1%\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"1\"/>
					Radio option 1</label>

					<label><input type=\"radio\" style=\"width:1%\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"1\"/>
					Radio option 2</label>
					<?php echo form_error('".$row["column_name"]."') ?>
				</div>
				</div>";
				break;
			case "dropdown":
				$string .= "\n\t    <div class=\" form-group\">
					 <label class=\"control-label col-md-3 col-sm-3 col-xs-12\" for=\"".$row["data_type"]."\">".label($row["column_name"])."</label>
					<div class=\"col-md-6 col-sm-6 col-xs-12\">
					<select class=\"form-control col-md-7 col-xs-12\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" >
					<option value=\"\">select</option>
					</select>
					<?php echo form_error('".$row["column_name"]."') ?>
				</div>
				</div>";
				break;
			case "password":
				$string .= "\n\t    <div class=\" form-group\">
					 <label class=\"control-label col-md-3 col-sm-3 col-xs-12\" for=\"".$row["data_type"]."\">".label($row["column_name"])."</label>
					<div class=\"col-md-6 col-sm-6 col-xs-12\">
					<input type=\"password\" class=\"form-control col-md-7 col-xs-12\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
					<?php echo form_error('".$row["column_name"]."') ?>
				</div>
				</div>";
				break;
			case "datepicker":
			    $datepicker_type = '1';
				$string .= "\n\t    <div class=\" form-group\">
					 <label class=\"control-label col-md-3 col-sm-3 col-xs-12\" for=\"".$row["data_type"]."\">".label($row["column_name"])."</label>
					<div class=\"col-md-6 col-sm-6 col-xs-12\">
					<input type=\"text\" class=\"date-picker form-control col-md-7 col-xs-12\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
					<?php echo form_error('".$row["column_name"]."') ?>
				</div>
				</div>";
				break;
			default:
				$string .= "";
		}
	}
	else{
    if ($row["data_type"] == 'text')
    {
		
    $string .= "\n\t    <div class=\" form-group\"> 
					<label class=\"control-label col-md-3 col-sm-3 col-xs-12\" for=\"".$row["column_name"]."\">".label($row["column_name"])."</label>
					<div class=\"col-md-6 col-sm-6 col-xs-12\">
					<textarea class=\"form-control col-md-7 col-xs-12\" rows=\"3\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\"><?php echo $".$row["column_name"]."; ?></textarea>
					<?php echo form_error('".$row["column_name"]."') ?>
				</div>
				</div>";
    } 
	
	elseif ($row["column_name"] == 'created' || $row["column_name"] == 'updated') {
		
		$string .= "\n\t";
	
	}	
	
	else
    {
    $string .= "\n\t    <div class=\" form-group\">
			 <label class=\"control-label col-md-3 col-sm-3 col-xs-12\" for=\"".$row["data_type"]."\">".label($row["column_name"])."</label>
            <div class=\"col-md-6 col-sm-6 col-xs-12\">
			<input type=\"text\" class=\"form-control col-md-7 col-xs-12\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
			<?php echo form_error('".$row["column_name"]."') ?>
		</div>
		</div>";
    }
	}
}
}
$string .= " <div class=\"ln_solid\"></div>
                            <div class=\"form-group\">
                                <div class=\"col-md-6 col-sm-6 col-xs-12 col-md-offset-3\">
                                    <button class=\"btn btn-success\" type=\"submit\">Submit</button>  
                                    <button class=\"btn btn-primary\" type=\"button\" onclick=\"window.location.href = '<?php echo base_url('".$c_url."/'); ?>';\">Cancel</button>

                                </div>
                            </div>";


$string .= "";
$string .= "\n\t		</form>
					</div>
				</div> 
			</div>
		</div> 
	</div>
</div>
<script type=\"text/javascript\">

var form2 = $('#frm_create');
        var error1 = $('.alert-danger', form2);
        var success1 = $('.alert-success', form2);

        form2.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: \"\",
            rules: {";
			foreach ($non_pk as $row) {
				if($row["column_name"] != 'created' && $row["column_name"] != 'updated'){
					if(!in_array($row["column_name"], $col_not_req)){
					if(!in_array($row["column_name"], $col_not_req_validation)){
						if(count($_POST['sel_validation_'.$row["column_name"]])){
							$string .= "\n\t        ".$row['column_name'].": {";
							if(in_array('required', $_POST['sel_validation_'.$row["column_name"]])){
								$string .= "\n\t             required: true,";
							}
							if(in_array('email', $_POST['sel_validation_'.$row["column_name"]])){
								$string .= "\n\t             email: true,";
							}
							if(in_array('is_unique', $_POST['sel_validation_'.$row["column_name"]])){
								$string .= "\n\t             remote: {
											url: \"<?php echo base_url(); ?>".$c."/check_exist\",
											type: \"post\",
											data: {
											  val: function() {
												return $( \"#".$row["column_name"]."\" ).val();
											  },
											  col : '".$row["column_name"]."'
											}
										  }";
							}
							if(in_array('date', $_POST['sel_validation_'.$row["column_name"]])){
								$string .= "\n\t             date: true,";
							}
							if(in_array('url', $_POST['sel_validation_'.$row["column_name"]])){
								$string .= "\n\t             url: true,";
							}
							if(in_array('number', $_POST['sel_validation_'.$row["column_name"]])){
								$string .= "\n\t             number: true,";
							}
							if(in_array('digits', $_POST['sel_validation_'.$row["column_name"]])){
								$string .= "\n\t             digits: true,";
							}
							$string .= "\n\t        },";
						}
						else{
							$string .= "\n\t        ".$row['column_name'].": {
							required: true
							},";
						}	
					}
					}
				}
			}
			
$string .= "\n\t        },
			messages: {";
foreach ($non_pk as $row) {
if(in_array('is_unique', $_POST['sel_validation_'.$row["column_name"]])){			
$string .= "\n\t        ".$row["column_name"].":{
                remote: \"Already taken! Try another.\"
            },";
			}
			}
$string .= "\n\t        },";

$string .= "highlight: function(element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group

                $(\".tab-content\").find(\"div.tab-pane:has(div.has-error)\").each(function(index, tab) {
                    var id = $(tab).attr(\"id\");
                    $('a[href=\"#' + id + '\"]').addClass('alert-danger');

                });

            },
            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group

            },
            success: function(label) {
                label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function(form) {
                    form.submit();
              
            }
        });";
		if($datepicker_type){
			$string .= "\n\t   $(document).ready(function() {
				$('.date-picker').daterangepicker({
					singleDatePicker: true,
					calender_style: \"picker_1\",
				}, function(start, end, label) {
					console.log(start.toISOString(), end.toISOString(), label);
				});
				});";
		}
		
$string .= "\n</script>";

$hasil_view_form = createFile($string, $target."views/" . $v_form_file);

?>