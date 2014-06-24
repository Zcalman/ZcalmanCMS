function calendarDayClick(year, month, day, type)
{
	if(type == "noteDay" || type == "toDayNoteDay")
	{
		m = fixDate(month);
		d = fixDate(day);
		attach_file(host + "plugins/calendar/getOndayAct.php?string=" + year + m + d);
	}
	else if(type == "fakeDay")
	{
		$("#OndayAct").html("");
		goto(host + "plugins/calendar/calendar.php?option1=" + year + "&option2=" + month);
		parent.$("#calendar_frame").trigger('calendar.needResize');
	}
	else if(type == "regularDay" || type == "toDay")
	{
		$("#OndayAct").html("");
		parent.$("#calendar_frame").trigger('calendar.needResize');
	}
}

function goto(to)
{
	self.location.href = to;
}

function attach_file( p_script_url ) {
      // create new script element, set its relative URL, and load it
      script = document.createElement( 'script' );
      script.src = p_script_url;
      document.getElementsByTagName( 'head' )[0].appendChild( script );
}

function fixDate(num)
{
	if(num < 10)
	{
		ret = "0" + num;
	}
	else
	{
		ret = num;
	}
	return ret;
}