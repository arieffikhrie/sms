<script type="text/javascript">
	$(document).ready(function() {
		$("#addRow").click(function (){   
			$('#items tbody>tr:first').clone(true).append('<td><button id="removeRow" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button></td>').insertAfter('#items tbody>tr:last');
			$('#items tbody>tr:last #qty').val('');
			$('#items tbody>tr:last #item').html("<option value=''>--Select--</option>");			
		});
		$('#items').on('click','#removeRow', function (){
			$(this).closest("tr").remove();
		});
		$('#items').on('change','#category', function (){
			var category = $(this).val();
			var item = $(this).closest("tr").find("#item");
			$.post("<?php echo base_url(); ?>main/get_item",
				{ categoryid:category},
				function(data){
					item.html(data);
			});
		});
		$('#items').on('change','#item', function (){
			var item = $(this).val();
			var unit = $(this).closest("tr").find("#unit");
			$.post("<?php echo base_url(); ?>main/get_item_unit",
				{ itemid:item},
				function(data){
					unit.val(data);
			});
		});
		
		$('#items').on('change','#qty', function (ev){
			var total = 0;
			$('tr').each(function(){
				var qty = $(this).find('#qty').val();
				if ( !isNaN(qty) )
					total += parseInt(qty);
			});
			$('#total').val(total);
		});
	});
</script>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo $title; ?></div>
	<div class="panel-body">
	<?php echo form_open_multipart('main/new_request',array('class'=>'form-horizontal', 'role'=>'form'));?>
	<div class="form-group">
		<?php echo form_label('RO Description','roDesc',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($roDesc);?>
			<?php echo form_error('roDesc', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label('RO Justification','roJustification',array('class'=>'col-sm-2 control-label'));?>
		<div class="col-sm-10">
			<?php echo form_input($roJustification);?>
			<?php echo form_error('roJustification', '<span class="label label-danger">', '</span>'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label("Department" ,"department",array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo form_dropdown('department',$departmentOption,$departmentValue,'class="form-control" disabled="disabled"'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label("Remark" ,"remark",array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo form_textarea($remark);?>
		</div>
	</div>
	<div class="form-group">
		<?php echo form_label("Attach file" ,"userFile",array('class'=>'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<a class='btn btn-primary' href='javascript:;'>Choose File...
			<?php 
				$file = array(
					'name' => 'userFile',
					'id' => 'userFile',
					'style' =>  'position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:\'progid:DXImageTransform.Microsoft.Alpha(Opacity=0)\';opacity:0;background-color:transparent;color:transparent;'
				);
				$js = "onchange='$(\"#upload-file-info\").html($(this).val());'";
			?><?php echo form_upload($file,'',$js); ?></a>
			<span class='label label-info' id="upload-file-info"></span>
		</div>
	</div>
	<div id="items" class="panel panel-default">
		<?php echo $rowItem; ?>
		<div class="panel-body">
			<div class="col-md-7">
				<?php echo form_button($addRow,'<span class="glyphicon glyphicon-plus"></span>','class="btn btn-success"'); ?>
				&nbsp;
				<?php echo form_error('itemid[]', '<span class="label label-danger">', '</span>'); ?>
				<?php echo form_error('qty[]', '<span class="label label-danger">', '</span>'); ?>
			</div>
			<div class="form-group">
				<label for="total" class="col-sm-1 control-label">Total</label>
				<div class="col-md-2">
					<input type="text" name="total" value="" id="total" class="form-control" disabled="disabled">
				</div>
			</div>
		</div>
	</div>
	<?php echo form_submit('submit','Submit','class="btn btn-default"'); ?>
	<?php echo form_close();?>
	</div>
</div>