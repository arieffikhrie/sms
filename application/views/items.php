<div class="panel panel-default">
	<div class="panel-heading">Items</div>
	<div class="panel-body">
		<div class="btn-new"><?php echo anchor('admin/new_items','Add new item',array('role'=>'button', 'class'=>'btn btn-default'));?></div>
		<?php echo $items_table; ?>
	</div>
</div>
