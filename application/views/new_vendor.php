<div class="panel panel-default">
	<div class="panel-heading"><?php echo $title; ?></div>
	<div class="panel-body">
	<?php echo form_open(uri_string(),array('class'=>'form-horizontal', 'role'=>'form'));?>
	<div class="form-group">
		<?php echo form_label('Category','category',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_multiselect($category['name'],$category['option'],$category['value'],$category['attr']); ?>
			<?php echo form_error('category', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Name','vendorName',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($vendorName);?>
			<?php echo form_error('vendorName', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Description','vendorDescription',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_textarea($vendorDescription);?>
			<?php echo form_error('vendorDescription', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Address','vendorAddress',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_textarea($vendorAddress);?>
			<?php echo form_error('vendorAddress', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Telephone','vendorTelephone',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($vendorTelephone);?>
			<?php echo form_error('vendorTelephone', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Fax','vendorFax',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($vendorFax);?>
			<?php echo form_error('vendorFax', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Email','vendorEmail',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($vendorEmail);?>
			<?php echo form_error('vendorEmail', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<?php echo form_submit('submit','Submit','class="btn btn-default"'); ?>
	<?php echo form_close();?>
	</div>
</div>
<script>
	$(document).ready(function() { 
		$("#category").multiselect({
			includeSelectAllOption: true,
			enableFiltering: true,
			maxHeight: 200,
			buttonWidth: 'auto'
		});
	});
</script>