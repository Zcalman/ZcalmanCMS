
function securityAskDeleteSlide(id)
{
	$('#zc_calendar_delete_act_dialog').dialog(
	{
		modal: true, 
		resizable: false, 
		buttons: { 
			"Radera": function() 
			{
				goto(host + "admin/plugin/calendar/delete/" + id);
			},
			"Avbryt": function() 
			{
				$(this).dialog("close");
			} 
		} 
	});
}

function clickActivity(id)
{
	goto(host + "admin/plugin/calendar/activity/" + id);
}

$(document).ready(function() 
{
	$("#date").datepicker({ 
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		dayNames: [ "Söndag", "Måndag", "Tisdag", "Onsdag", "Torsdag", "Fredag", "Lördag" ],
		dayNamesMin: [ "Sö", "Må", "Ti", "On", "To", "Fr", "Lö" ],
		monthNames: [ "Januari", "Februari", "Mars", "April", "Maj", "Juni", "Juli", "Augusti", "September", "Oktober", "November", "December" ],
		nextText: "Nästa",
		prevText: "Föregående",
	});
});