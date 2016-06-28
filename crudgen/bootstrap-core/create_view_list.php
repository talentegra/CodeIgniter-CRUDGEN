<?php 

$string = "<!-- page content -->
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
                        <div class=\"clearfix\"></div>

                    </div>
                    <div class=\"x_content\">		



        
        <div class=\"row\" style=\"margin-bottom: 10px\">
            <div class=\"col-md-4 text-center\">
                <div style=\"margin-top: 8px\" id=\"message\">
                    <?php echo \$this->session->userdata('message') <> '' ? \$this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class=\"col-md-5 text-right\">
            </div>
            <div class=\"col-md-3 text-right\">
                    <div class=\"input-group\">
                        <input type=\"text\" class=\"form-control\"  id=\"q\" name=\"q\" value=\"<?php echo \$q; ?>\">
                        <span class=\"input-group-btn\" id=\"btn_grp\">
                            <?php 
                                if (\$q <> '')
                                {
                                    ?>
                                    <a href=\"<?php echo site_url('$c_url'); ?>\" class=\"btn btn-default\">Reset</a>
                                    <?php
                                }
                            ?>
                          <button id =\"search_q\" class=\"btn btn-primary\" type=\"submit\">Search</button>
                        </span>
                    </div>
            </div>
        </div>
        <input type=\"hidden\" name=\"sortby\" id=\"sortby\" value=\"<?php echo \$sort_by; ?>\" />
                    <input type=\"hidden\" name=\"sort_column\" id=\"sort_column\" value=\"<?php echo \$sort_column; ?>\" /> 
                    <div class=\"x_content\" id=\"data_list\"></div>
        <div class=\"row\">
            <div class=\"col-md-6\">
                <a href=\"#\" class=\"btn btn-primary\">Total Record :  <span id=\"span_total_rec\" ></span></a>";
if ($export_excel == '1') {
    $string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/excel'), 'Excel', 'class=\"btn btn-primary\"'); ?>";
}
if ($export_word == '1') {
    $string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/word'), 'Word', 'class=\"btn btn-primary\"'); ?>";
}
if ($export_pdf == '1') {
    $string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/pdf'), 'PDF', 'class=\"btn btn-primary\"'); ?>";
}
$string .= "\n\t    </div>
        </div>
   
   
      </div>
                </div>
            </div>

            <div class=\"clearfix\"></div>
        </div>
    </div>
</div>
<!-- /page content -->
 <script type='text/javascript'>
    
    $(document).ready(function() {
        load_list(0);

        $('body').on('click', '.pagination_link', function() {
            //alert('clicked '+$(this).data('count'));
            var page = $(this).data('count');

            load_list(page);
        });

        $('body').on('change', '#per_page', function() {

            load_list(0);
        });
            
        $('body').on('click', '.one_field_arrange_option', function() {
                
            var sortby = $('#sortby').val();
            var sort_column = $(this).data('sortdb');
            if (sortby == '')
            {
                sortby = 'DESC';
                $('#' + sort_column).prepend('<i class=\"icon-sort-down\"></i>');
            }
            else if (sortby == 'DESC')
            {
                sortby = 'ASC';
                $('#' + sort_column).prepend('<i class=\"icon-sort-up\"></i>');
            }
            else if (sortby == 'ASC')
            {
                sortby = 'DESC';
                $('#' + sort_column).prepend('<i class=\"icon-sort-down\"></i>');
            }
                
            $('#sortby').val(sortby);
            $('#sort_column').val(sort_column);
                
            load_list(0);
        });
            
            

    });
        
    function load_list(page) {
        $('#dataloaderimage').show();
        var per_page = $('select#per_page option').filter(':selected').val();
        var sortby = $('#sortby').val();
        var sort_column = $('#sort_column').val();
		var q = $('#q').val();
        var dataString = 'per_page=' + per_page + '&page=' + page+ '&sortby=' + sortby+ '&sort_column=' + sort_column+ '&q=' + q;
        $('#dataloaderimage').show();
        $.ajax({
            type: 'POST',
            data: dataString,
            url: '<?php echo base_url(); ?>".$view_name."/list_data/' + page, //The url where the server req would we made.

            success: function(data) {
				$('#btn_reset').remove();
                $('#overlay').remove();
                $('#dataloaderimage').hide();
                $('#data_list').html(data).fadeIn('slow');
				$('#span_total_rec').html($('#hid_total_rows').val());
				if($('#q').val()!=''){
						$( '#btn_grp' ).prepend( '<a id=\"btn_reset\" href=\"\" class=\"btn btn-default\">Reset</a>' );
					}
					else {
						$('#btn_reset').remove();
					}
                if(sort_column!=''){
					if(sort_column != '".$pk."'){
                    var string = $('#'+sort_column).html();
                    if(sortby=='ASC'){
                        string = string.replace(/fa-sort|fa fa-sort-desc|fa fa-sort-asc/gi, 'fa fa-sort-asc');
                    }
                    else{
                        string = string.replace(/fa-sort|fa fa-sort-desc|fa fa-sort-asc/gi, 'fa fa-sort-desc');	
                    }
                    $('#'+sort_column).html(string);
				}
                    //alert(string);

                }
					
            }
        });
    }
    
    $('#search_q').click(function() {
            load_list(0);
    });
	$('#btn_reset').click(function() {
            load_list(0);
    });
	
</script>     
   
   ";


$hasil_view_list = createFile($string, $target."views/" . $v_list_file);

?>