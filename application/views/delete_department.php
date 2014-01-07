<div class="panel panel-default">
	<div class="panel-heading"><?php echo $title ?></div>
	<div class="panel-body">
		<?php echo form_open(uri_string(),array('class'=>'form-horizontal', 'role'=>'form'));?>
		<p>Are you sure want to delete department '<?php echo $department->department_name; ?>'</p>
		<?php echo form_hidden('action','delete'); ?>
		<div class="btn-group">
			<?php echo form_submit('submit','Yes','class="btn btn-default"'); ?>
			<a href="<?php echo base_url(); ?>admin/departments" class="btn btn-default" role="button">No</a>
		</div>
		<?php echo form_close();?>
	</div>
</div>