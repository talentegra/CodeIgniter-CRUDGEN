<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <link rel="stylesheet" href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <h2 style="margin-top:0px">Customer List</h2>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 4px"  id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <?php echo anchor(site_url('customer/create'), 'Create', 'class="btn btn-primary"'); ?>
	    </div>
        </div>
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th width="80px">No</th>
		    <th>Name</th>
		    <th>Cust Type</th>
		    <th>Manager</th>
		    <th>Manager Mobile</th>
		    <th>Status</th>
		    <th>Domain</th>
		    <th>Extra</th>
		    <th>Created</th>
		    <th>Updated</th>
		    <th>Action</th>
                </tr>
            </thead>
	    <tbody>
            <?php
            $start = 0;
            foreach ($customer_data as $customer)
            {
                ?>
                <tr>
		    <td><?php echo ++$start ?></td>
		    <td><?php echo $customer->name ?></td>
		    <td><?php echo $customer->cust_type ?></td>
		    <td><?php echo $customer->manager ?></td>
		    <td><?php echo $customer->manager_mobile ?></td>
		    <td><?php echo $customer->status ?></td>
		    <td><?php echo $customer->domain ?></td>
		    <td><?php echo $customer->extra ?></td>
		    <td><?php echo $customer->created ?></td>
		    <td><?php echo $customer->updated ?></td>
		    <td style="text-align:center" width="200px">
			<?php 
			echo anchor(site_url('customer/read/'.$customer->id),'Read'); 
			echo ' | '; 
			echo anchor(site_url('customer/update/'.$customer->id),'Update'); 
			echo ' | '; 
			echo anchor(site_url('customer/delete/'.$customer->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
			?>
		    </td>
	        </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#mytable").dataTable();
            });
        </script>
    </body>
</html>