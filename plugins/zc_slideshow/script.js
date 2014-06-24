$(document).ready(
	function() {
		$("#zc_slideshow_sort").sortable({
			axis: 'y',
			update : function () {
				startLoad("Sparar");
				serial = $('#zc_slideshow_sort').sortable('serialize');
				$.ajax({
					url: host + "plugins/zc_slideshow/sortlist.php",
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


function securityAskDeleteSlide(id)
{
	$('#zc_slideshow_delete_slide_dialog').dialog(
	{
		modal: true, 
		resizable: false, 
		buttons: { 
			"Radera": function() 
			{
				goto(host + "admin/plugin/zc_slideshow/delete/" + id);
			},
			"Avbryt": function() 
			{
				$(this).dialog("close");
			} 
		} 
	});
}