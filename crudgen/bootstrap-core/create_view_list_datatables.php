<?php 

$string = "


<!-- page content -->
<div class=\"right_col\" role=\"main\">
    <div class=\"\">
        <div class=\"page-title\">
            <div class=\"title_left\">
                <h3><small></small></h3>
            </div>

            <div class=\"title_right\">
                <div class=\"col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search\">
                    <div class=\"input-group\">
                        <input type=\"text\" class=\"form-control\" placeholder=\"Search for...\">
                        <span class=\"input-group-btn\">
                            <button class=\"btn btn-default\" type=\"button\">Go!</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class=\"clearfix\"></div>

        <div class=\"row\">
            <div class=\"col-md-12 col-sm-12 col-xs-12\">
                <div class=\"x_panel\">
                    <div class=\"x_title\">
					
			   <h2>" .ucfirst($table_name). " List <small>Manage " .ucfirst($table_name). "</small></h2>
                        <div style=\"float: right;\"><a href=\"<?php echo base_url('".$c_url."/create'); ?>\"><button class=\"btn btn-success\" type=\"button\">Create " .ucfirst($table_name). "</button></a></div>
                        <div class=\"clearfix\"></div>";

                

				

$string .= "\n\t    </div>

				<div class=\"x_content\">
					  <table class=\"table table-striped\">
                            <thead>	


    
                <tr>
                    <th width=\"80px\">No</th>";
foreach ($non_pk as $row) {
    $string .= "\n\t\t    <th>" . label($row['column_name']) . "</th>";
}
$string .= "\n\t\t    <th>Action</th>
                </tr>
            </thead>";
$string .= "\n\t    <tbody>
            <?php
            \$start = 0;
            foreach ($" . $c_url . "_data as \$$c_url)
            {
                ?>
                <tr>";

$string .= "\n\t\t    <td><?php echo ++\$start ?></td>";

foreach ($non_pk as $row) {
    $string .= "\n\t\t    <td><?php echo $" . $c_url ."->". $row['column_name'] . " ?></td>";
}

$string .= "\n\t\t    <td style=\"text-align:center\" width=\"200px\">

<div class=\"btn-group\">
      <button type=\"button\" class=\"btn btn-danger dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\"> Action <span class=\"caret\"></span> </button>
	   <ul class=\"dropdown-menu\">
	    <li><a href=\"<?php echo site_url('".$c_url."/read/'.$".$c_url."->".$pk."); ?>\">Read</a>
                                                    </li>
	      <li><a href=\"<?php echo site_url('".$c_url."/update/'.$".$c_url."->".$pk."); ?>\">Edit</a>
                                                    </li>
                                                    <li><a href=\"<?php echo site_url('".$c_url."/delete/'.$".$c_url."->".$pk."); ?>\">Delete</a>
                                                    </li>
	  
	   </ul>


</div> </td>

";

$string .=  "\n\t        </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
   
    
	
	
      </div>
                </div>
            </div>

            <div class=\"clearfix\"></div>
        </div>
    </div>
</div>
<!-- /page content -->
	
	";


$hasil_view_list = createFile($string, $target."views/" . $v_list_file);

?>