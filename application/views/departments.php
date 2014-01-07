<div class="panel panel-default">
	<div class="panel-heading"><?php echo $title; ?></div>
	<div class="panel-body">
		<div class="btn-new"><?php echo anchor('admin/new_department','Add new department',array('role'=>'button', 'class'=>'btn btn-default'));?></div>
		<?php echo $departments_table; ?>
	</div>
</div>