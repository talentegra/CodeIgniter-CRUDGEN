<?php 

$string = "

<!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
    <!-- Content Header (Page header) -->
    <section class=\"content-header\">
      <h1>
       ".ucfirst($c_url)."
        <small>".ucfirst($c_url)." List</small>
      </h1>
      <ol class=\"breadcrumb\">
        <li><a href=\"<?php echo base_url(); ?>\"><i class=\"fa fa-dashboard\"></i> Home</a></li>
        <li><a href=\"<?php echo base_url('".$c_url."'); ?>\">".ucfirst($c_url)."</a></li>
        <li class=\"active\">".ucfirst($c_url)." List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class=\"content\">

      <!-- Default box -->
      <div class=\"box\">
        <div class=\"box-header with-border\">
          <h3 class=\"box-title\">".ucfirst($c_url)."</h3>

          <div class=\"box-tools pull-right\">
            <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\" data-toggle=\"tooltip\" title=\"Collapse\">
              <i class=\"fa fa-minus\"></i></button>
            <button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"remove\" data-toggle=\"tooltip\" title=\"Remove\">
              <i class=\"fa fa-times\"></i></button>
          </div>
        </div>
        <div class=\"box-body table-responsive no-padding\">

<div role=\"main\" class=\"right_col\" style=\"min-height: 3687px;\">
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
</div>

</div>
        <!-- /.box-body -->
        <div class=\"box-footer\">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
	 


";		



$hasil_view_read = createFile($string, $target."views/" . $v_read_file);

?>