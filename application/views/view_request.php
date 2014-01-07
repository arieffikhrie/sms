<p><?php echo anchor('main','Back');?></p>

<div class="panel panel-default">
	<div class="panel-heading"><?php echo $title; ?></div>
	<div class="panel-body">
		<p><span>RO No: </span><span><?php echo $ro->roID; ?></span></p>
		<p><span>Description: </span><span><?php echo $ro->roDesc; ?></span></p>
		<p><span>Justification: </span><span><?php echo $ro->roJustification; ?></span></p>
		<p><span>Date: </span><span><?php echo date('d/m/Y', strtotime($ro->roDate)); ?></span></p>
		<p><span>Attachment: </span><span><a href="../../files/<?php echo $ro->fileUrl; ?>"><?php echo $ro->fileUrl; ?></a></span></p>
		<p><span>Remark: </span><span><?php echo $ro->remark; ?></span></p>
		<div><?php echo $table; ?></div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Request Log</div>
					<div class="panel-body">
						<?php echo $log_table; ?>
					</div>
				</div>
			</div>


