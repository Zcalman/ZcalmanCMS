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
include("../../../../core/language/" . LANGUAGE . ".php");
$string = safeText(g('string'));
$id = (int)safeText(g('id'));
$section_id = (int)safeText(g('sid'));
$sql = mysql_query("SELECT slug FROM sections WHERE id = {$section_id}");
$s = mysql_fetch_assoc($sql);
$section_slug = $s['slug'];
$slug = getSlug($string);
$finded = false;
$counter = 1;
do 
{
	$sql = mysql_query("SELECT id FROM sections_pages WHERE slug = '{$slug}' AND section_id = {$section_id} AND id != {$id}");
	if(mysql_num_rows($sql) == 0)
	{
		$finded = true;
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

var targetDiv = document.getElementById("sluglink");
targetDiv.innerHTML = "<?PHP host(); echo $section_slug . "/" . $slug; ?>";