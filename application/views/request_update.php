			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Update</div>
					<div class="panel-body">
						<?php echo form_open("main/request_update");?>
							<?php echo form_hidden('roID',$ro->roID);?>		
							<div class="form-group">
								<?php echo form_label('Remark','remark',array('class'=>'col-sm-2 control-label'));?>
								<?php echo form_textarea($remark);?>
							</div>
							<?php echo form_button($updateBtn); ?>
							<?php echo form_button($rejectBtn); ?>
						<?php echo form_close();?>
					</div>
				</div>
			</div>

