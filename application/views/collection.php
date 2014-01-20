<div class="panel panel-default">
	<div class="panel-heading"><?php echo $title; ?></div>
	<div class="panel-body">
		<?php echo form_open(uri_string(),array('class'=>'form-horizontal', 'role'=>'form'));?>
		<?php echo $table; ?>
		<?php echo form_submit('submit','Collect','class="btn btn-default"'); ?>
		<?php echo form_close();?>
	</div>
</div>