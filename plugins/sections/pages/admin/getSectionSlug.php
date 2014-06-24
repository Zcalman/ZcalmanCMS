<?PHP
// Ställer in tidszon
date_default_timezone_set("Europe/Stockholm");
header( 'Content-Type: text/javascript' );

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();
?>
var string = "<?PHP 

require("../../../../zc_settings.php");
$objConn = mysql_connect ($dbhost, $dbuser, $dbpass);
mysql_select_db($db);
require("../../../../core/core_functions.php");
require("../../../../admin/admin_functions.php");
defineHOST();
$host = HOST;
protect();
defineLanguage();
require("../../../../core/pages.php");
include("../../../../core/language/" . LANGUAGE . ".php");
$string = safeText(g('string'));
$id = (int)safeText(g('id'));
$slug = getSlug($string);
$finded = false;
$counter = 1;
do 
{
	$sql = mysql_query("SELECT id FROM sections WHERE slug = '{$slug}' AND id != {$id}");
	if(mysql_num_rows($sql) == 0)
	{
		if(!array_key_exists($slug, $pages))
		{
			$finded = true;
		}
		else
		{
			$slug = $slug . "-" . $counter;
			$counter++;
		}
	}
	else
	{
		$slug = $slug . "-" . $counter;
		$counter++;
	}
}while(!$finded);
?>
";

if(string != "")
{
	alert("Message from autoupdatescript: " + string);
}


var targetfield = document.getElementById("slug");
targetfield.value = "<?PHP echo $slug; ?>";

var targetDiv = document.getElementById("pagelink");
targetDiv.innerHTML = "Länk: <?PHP host(); echo $slug; ?> <a href='#' id='edit_slug_link' onclick='javascript: editSectionSlug();'>Ändra</a>";