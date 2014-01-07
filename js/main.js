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
});