<div class="panel panel-default">
	<div class="panel-heading"><?php echo $title; ?></div>
	<div class="panel-body">
	<?php echo form_open(uri_string(),array('class'=>'form-horizontal', 'role'=>'form'));?>
	<div class="form-group">
		<?php echo form_label('Name','department_name',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($department_name);?>
			<?php echo form_error('department_name', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Description','department_desc',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_textarea($department_desc);?>
			<?php echo form_error('department_desc', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<?php echo form_submit('submit','Submit','class="btn btn-default"'); ?>
	<?php echo form_close();?>
	</div>
</div>