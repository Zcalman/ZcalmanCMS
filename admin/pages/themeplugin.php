<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
$plugin = get_setting("theme");
$theme = readTheme($plugin);
if($theme['plugin'] == true)
{
	loadPlugin($plugin, "show_adminpage", false, "theme");
}
else
{
	adminerror(404);
}
?>