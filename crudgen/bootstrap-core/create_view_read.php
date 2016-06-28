<?php 

$string = "<div role=\"main\" class=\"right_col\" style=\"min-height: 3687px;\">
    <div class=\"\">
        <div class=\"page-title\">
            <div class=\"title_left\">
                <h3>Read " .ucfirst($table_name). "</h3>
            </div>

        </div>

	<div class=\"clearfix\"></div>
        <div class=\"row\">
            <div class=\"col-md-12 col-sm-12 col-xs-12\">
                <div class=\"x_panel\">

                    <div class=\"x_content\">
                        <br>



        <table class=\"table\">";
foreach ($non_pk as $row) {
    $string .= "\n\t    <tr><td>".label($row["column_name"])."</td><td><?php echo $".$row["column_name"]."; ?></td></tr>";
}
$string .= "\n\t    <tr><td></td><td><a href=\"<?php echo site_url('".$c_url."') ?>\" class=\"btn btn-default\">Cancel</a></td></tr>";
$string .= "\n\t</table>
        ";
		



$string .= "";
$string .= "\n\t		
					</div>
				</div> 
			</div>
		</div> 
	</div>
</div>";		



$hasil_view_read = createFile($string, $target."views/" . $v_read_file);

?>