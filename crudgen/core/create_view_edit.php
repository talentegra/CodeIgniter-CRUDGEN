<?php 

$string = "<h2 style=\"margin-top:0px\">".ucfirst($table_name)." <?php echo \$button ?></h2>
        <form action=\"<?php echo \$action; ?>\" method=\"post\">
		<div class=\"panel panel-default\">
    <div class=\"panel-heading\"><i class=\"glyphicon glyphicon-signal\"></i> </div>
	<div class=\"panel-body\">";
foreach ($non_pk as $row) {
    
	
	if ($row["data_type"] == 'text')
    {
    $string .= "\n\t    <div class=\"form-group\">
            <label class=\"col-sm-2\" for=\"".$row["column_name"]."\">".label($row["column_name"])." <?php echo form_error('".$row["column_name"]."') ?></label>
            <div class=\"col-sm-6\">
			<textarea class=\"form-control\" rows=\"3\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\"><?php echo $".$row["column_name"]."; ?></textarea>
        </div>
		</div>";
    } elseif ($row["column_name"] == 'created' || $row["column_name"] == 'updated') {
		
		$string .= "\n\t";
	
	}	
	
	else
    {
    $string .= "\n\t    <div class=\"form-group\">
            <label class=\"col-sm-2\" for=\"".$row["data_type"]."\">".label($row["column_name"])." <?php echo form_error('".$row["column_name"]."') ?></label>
            <div class=\"col-sm-6\">
			<input type=\"text\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
        </div>
		</div>";
    }
}
$string .= " </div> <!--/ Panel Body -->
    <div class=\"panel-footer\">   
          <div class=\"row\">
		 <div class=\"col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0\">	
     ";
$string .= "\n\t    <input type=\"hidden\" name=\"".$pk."\" value=\"<?php echo $".$pk."; ?>\" /> ";
$string .= "\n\t    <button type=\"submit\" class=\"btn btn-primary\"><?php echo \$button ?></button> ";
$string .= "\n\t    <a href=\"<?php echo site_url('".$c_url."') ?>\" class=\"btn btn-default\">Cancel</a>";
$string .= "\n\t    </div></div>
    </div><!--/ Panel Footer -->       
</div><!--/ Panel -->";
$string .= "\n\t</form>";

$hasil_view_edit = createFile($string, $target."views/" . $v_edit_file);

?>