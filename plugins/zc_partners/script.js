$(document).ready(
	function() {
		$("#zc_partners_sort").sortable({
			axis: 'y',
			update : function () {
				startLoad("Sparar");
				serial = $('#zc_partners_sort').sortable('serialize');
				$.ajax({
					url: host + "plugins/zc_partners/sortlist.php",
					type: "post",
					data: serial,
					error: function(){
						alert("Theres an error with AJAX");
					}
				});
			}
		});
	}
);


function securityAskDeletePartner(id)
{
	$('#zc_slideshow_delete_partner_dialog').dialog(
	{
		modal: true, 
		resizable: false, 
		buttons: { 
			"Radera": function() 
			{
				goto(host + "admin/plugin/zc_partners/delete/" + id);
			},
			"Avbryt": function() 
			{
				$(this).dialog("close");
			} 
		} 
	});
}