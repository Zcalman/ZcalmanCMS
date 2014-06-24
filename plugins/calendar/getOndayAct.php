<?PHP
// Ställer in tidszon
date_default_timezone_set("Europe/Stockholm");
header( 'Content-Type: text/javascript' );

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();
?>
var string = "<?PHP 

require("../../zc_settings.php");
$objConn = mysql_connect ($dbhost, $dbuser, $dbpass);
mysql_select_db($db);
require("../../core/core_functions.php");
require("../../admin/admin_functions.php");
defineHOST();
$host = HOST;
defineLanguage();
include("../../core/language/" . LANGUAGE . ".php");


$string = safeText(g('string'));
$sql = mysql_query("SELECT * FROM zc_calendar WHERE date = {$string}");
$html = "<div id='date'>" . splitToDate($string, "j/n - Y") . "</div>";
while($r = mysql_fetch_assoc($sql))
{
	$html .= "<div class='dayact'><h3>{$r['title']}</h3>{$r['text']}</div>";
}
?>
";

if(string != "")
{
	alert("Message from autoupdatescript: " + string);
}

$("#OndayAct").html("<?PHP echo $html; ?>");
parent.$("#calendar_frame").trigger('calendar.needResize');