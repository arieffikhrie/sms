<div class="panel panel-default">
	<div class="panel-heading"><?php echo $title; ?></div>
	<div class="panel-body">
		<div class="btn-new"><?php echo anchor('admin/create_user','Add new user',array('role'=>'button', 'class'=>'btn btn-default'));?></div>
		<?php echo $users; ?>
	</div>
</div>