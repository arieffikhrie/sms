<div class="panel panel-default">
	<div class="panel-heading"><?php echo $title; ?></div>
	<div class="panel-body">
	<?php echo form_open(uri_string(),array('class'=>'form-horizontal', 'role'=>'form'));?>
	<div class="form-group">
		<?php echo form_label('Username','username',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($username);?>
			<?php echo form_error('username', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Password','password',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($password);?>
			<?php echo form_error('password', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Confirm Password','password_confirm',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($password_confirm);?>
			<?php echo form_error('password_confirm', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Name','name',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($name);?>
			<?php echo form_error('name', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Email','email',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($email);?>
			<?php echo form_error('email', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Staff ID','staffid',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($staffid);?>
			<?php echo form_error('staffid', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('Phone','phone',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($phone);?>
			<?php echo form_error('phone', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label("Department" ,"department",array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo form_dropdown($department['name'],$department['option'],$department['value'],$department['attr']); ?>
			<?php echo form_error('department', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label("Groups" ,"groups",array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10 btn-group" data-toggle="buttons">
			<?php foreach ($groups as $group):?>
			<label class="btn btn-default">
			<?php
				$gID=$group['id'];
				$checked = null;
				$item = null;
			?>
			<input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>">
			<?php echo $group['name'];?>
			</label>
		<?php endforeach?>
		</div>
	</div>
	<?php echo form_submit('submit','Submit','class="btn btn-default"'); ?>
	<?php echo form_close();?>
	</div>
</div>