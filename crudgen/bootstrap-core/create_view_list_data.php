<?php 

$string = "<table class=\"table table-bordered\" style=\"margin-bottom: 10px\">
            <tr>
                <th>No</th>";
foreach ($non_pk as $row) {
    $string .= "\n\t\t<th style=\"cursor: pointer;\" id=\"".$row['column_name']."\" class=\"one_field_arrange_option\" data-sortdb=\"".$row['column_name']."\">" . label($row['column_name']) . "<i class=\"fa fa-sort\"></i></th>";
}
$string .= "\n\t\t<th>Action</th>
            </tr>";
$string .= "<?php
            foreach ($" . $c_url . "_data as \$$c_url)
            {
                ?>
                <tr>";

$string .= "\n\t\t\t<td width=\"80px\"><?php echo ++\$page ?></td>";
foreach ($non_pk as $row) {
    $string .= "\n\t\t\t<td><?php echo $" . $c_url ."->". $row['column_name'] . " ?></td>";
}


$string .= "\n\t\t\t<td style=\"text-align:center\" width=\"200px\">"
		. "\n\t\t\t\t<div class=\"btn-group\">"
		. "\n\t\t\t\t<button type=\"button\" class=\"btn btn-danger dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\"> Action <span class=\"caret\"></span> </button>"
		. "\n\t\t\t\t<ul class=\"dropdown-menu\">"
		. "<li><a href=\"<?php echo site_url('".$c_url."/read/'.$".$c_url."->".$pk."); ?>\">Read</a></li>"
		. "<li><a href=\"<?php echo site_url('".$c_url."/update/'.$".$c_url."->".$pk."); ?>\">Edit</a></li>"
		. "<li><a href=\"<?php echo site_url('".$c_url."/delete/'.$".$c_url."->".$pk."); ?>\">Delete</a></li>"
        . "\n\t\t\t</ul></div></td>";

$string .=  "\n\t\t</tr>
                <?php
            }
            ?>
        </table>
        ";

$string .= "<?php echo \$this->ajax_pagination->create_links(); ?>";
$string .= "<input type=\"hidden\" id=\"hid_total_rows\" name=\"hid_total_rows\" value=\"<?php echo \$total_rows; ?>\" />";
$hasil_view_list = createFile($string, $target."views/" . $v_list_data_file);

?>