<div class="panel panel-default">
	<div class="panel-heading">Edit Item</div>
	<div class="panel-body">
	<?php echo form_open(uri_string(),array('class'=>'form-horizontal', 'role'=>'form'));?>
	<div class="form-group">
		<?php echo form_label('Name','item_name',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($item_name);?>
			<?php echo form_error('item_name', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label("Category" ,"category_id",array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo form_dropdown($category_id['name'],$category_id['option'],$category_id['value'],$category_id['attr']); ?>
			<?php echo form_error('category_id', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Quantity','item_qty',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($item_qty);?>
			<?php echo form_error('item_qty', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Minimum Quantity','item_min_qty',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($item_min_qty);?>
			<?php echo form_error('item_min_qty', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label("Unit" ,"item_unit",array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo form_dropdown($item_unit['name'],$item_unit['option'],$item_unit['value'],$item_unit['attr']); ?>
			<?php echo form_error('item_unit', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<?php echo form_submit('submit','Submit','class="btn btn-default"'); ?>
	<?php echo form_close();?>
	</div>
</div>