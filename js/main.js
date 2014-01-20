/* Table initialisation */
$(document).ready(function() {
	$('.datatable').dataTable( {
	
		"sDom": "<'pull-left'f><'pull-right'l>t<'pull-right'p>",
		"sPaginationType": "bootstrap",
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page",
			"sSearch": ""
		}
	} );
	$('div.dataTables_filter input').addClass('form-control').attr("placeholder","Search");
	$('div.dataTables_length select').addClass('form-control');
	
	$("#select_all").click(function(){
		var checked_status = this.checked;
		$("input[name='item_selected[]']").each(function(){
			this.checked = checked_status;
		});
	});
	
	$("input[name='item_selected[]']").click(function(){
		if ( !this.checked ){
			$("#select_all").each(function(){
				this.checked = false;
			});
		}
	});

});