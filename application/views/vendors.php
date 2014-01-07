<div class="panel panel-default">
	<div class="panel-heading"><?php echo $title; ?></div>
	<div class="panel-body">
		<div class="btn-new"><?php echo anchor('admin/new_vendor','Add new vendor',array('role'=>'button', 'class'=>'btn btn-default'));?></div>
		<?php echo $vendors_table; ?>
	</div>
</div>
