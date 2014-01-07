<?php if($hrm){ ?>
<div class="panel panel-default">
	<div class="panel-heading">HRM</div>
	<div class="panel-body">
		<?php echo $hrm_table; ?>
	</div>
</div>
<?php } ?>
<?php if($admin){ ?>
<div class="panel panel-default">
	<div class="panel-heading">Admin</div>
	<div class="panel-body">
		<?php echo $admin_table; ?>
	</div>
</div>
<?php } ?>
<?php if($hod){ ?>
<div class="panel panel-default">
	<div class="panel-heading">HOD</div>
	<div class="panel-body">
		<?php echo $hod_table; ?>
	</div>
</div>
<?php } ?>
<?php if($members){ ?>
<div class="panel panel-default">
	<div class="panel-heading">Requestor</div>
	<div class="panel-body">
		<div class="btn-new"><a href="<?php echo base_url(); ?>main/new_request" role="button" class="btn btn-default">Add new request</a></div>
		<?php echo $members_table; ?>		
	</div>
</div>
<?php } ?>