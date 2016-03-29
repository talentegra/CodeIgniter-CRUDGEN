<h2 style="margin-top:0px">Customer <?php echo $button ?></h2>
        <form class="form-horizontal" action="<?php echo $action; ?>" method="post"> 
		<div class="panel panel-default">
    <div class="panel-heading"><i class="glyphicon glyphicon-signal"></i> </div>
	<div class="panel-body">
	    <div class=" form-group">
			 <label class="col-sm-2" for="varchar">Name <?php echo form_error('name') ?></label>
            <div class="col-sm-6">
			<input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
        </div></div>
	    <div class=" form-group">
			 <label class="col-sm-2" for="varchar">Cust Type <?php echo form_error('cust_type') ?></label>
            <div class="col-sm-6">
			<input type="text" class="form-control" name="cust_type" id="cust_type" placeholder="Cust Type" value="<?php echo $cust_type; ?>" />
        </div></div>
	    <div class=" form-group">
			 <label class="col-sm-2" for="varchar">Manager <?php echo form_error('manager') ?></label>
            <div class="col-sm-6">
			<input type="text" class="form-control" name="manager" id="manager" placeholder="Manager" value="<?php echo $manager; ?>" />
        </div></div>
	    <div class=" form-group">
			 <label class="col-sm-2" for="varchar">Manager Mobile <?php echo form_error('manager_mobile') ?></label>
            <div class="col-sm-6">
			<input type="text" class="form-control" name="manager_mobile" id="manager_mobile" placeholder="Manager Mobile" value="<?php echo $manager_mobile; ?>" />
        </div></div>
	    <div class=" form-group">
			 <label class="col-sm-2" for="int">Status <?php echo form_error('status') ?></label>
            <div class="col-sm-6">
			<input type="text" class="form-control" name="status" id="status" placeholder="Status" value="<?php echo $status; ?>" />
        </div></div>
	    <div class=" form-group">
			 <label class="col-sm-2" for="varchar">Domain <?php echo form_error('domain') ?></label>
            <div class="col-sm-6">
			<input type="text" class="form-control" name="domain" id="domain" placeholder="Domain" value="<?php echo $domain; ?>" />
        </div></div>
	    <div class=" form-group"> 
            <label class="col-sm-2" for="extra">Extra <?php echo form_error('extra') ?></label>
            <div class="col-sm-6">
			<textarea class="form-control" rows="3" name="extra" id="extra" placeholder="Extra"><?php echo $extra; ?></textarea>
        </div>
		</div>
	
	 </div> <!--/ Panel Body -->
    <div class="panel-footer">   
          <div class="row">
		 <div class="col-md-10 col-sm-12 col-md-offset-2 col-sm-offset-0">	
     
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('customer') ?>" class="btn btn-default">Cancel</a>
	    </div></div>
    </div><!--/ Panel Footer -->       
</div><!--/ Panel -->
	</form>
    </body>
</html>