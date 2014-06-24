<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
if(defined("OPT1") && trim(OPT1) != "" && OPT1 != NULL)
{
	$plugin = safeText(OPT1);
	$sql = mysql_query("SELECT * FROM plugins WHERE slug = '{$plugin}'");
	if(mysql_num_rows($sql) == 1)
	{
		$r = mysql_fetch_assoc($sql);
		if($r['active'] == 1)
		{
			loadPlugin($plugin, "show_adminpage", false);
		}
		else
		{
			echo "<div class=\"messagebox\">Det här tillägget är inte aktivt för tillfället</div>";
			loadPlugin($plugin, "show_adminpage", true);
		}
	}
	else
	{
		adminerror(404);
	}
}
else
{
	adminerror(404);
}
?>