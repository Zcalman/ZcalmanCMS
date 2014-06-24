<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

require("pages.php"); 

if (isset($_GET['page']) && $_GET['page'] != "") 
{
	$page = $_GET['page'];
}
else 
{
	$page = "start";
}
$folder = "pages/";
if (isset($pages[$page])) 
{
	if(file_exists($folder.$pages[$page]))
	{
		$content = $folder.$pages[$page];
	}
	else
	{
		$content = $folder."404.php";
	}
}
else 
{
	$content = $folder."404.php";
}
define("CONTENT", $content);
define("PAGE", $page);
?>