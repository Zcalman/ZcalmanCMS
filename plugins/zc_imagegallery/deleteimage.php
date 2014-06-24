var string = "<?PHP 
// Ställer in tidszon
date_default_timezone_set("Europe/Stockholm");
header( 'Content-Type: text/javascript' );

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

require("../../zc_settings.php");
session_start();
$objConn = mysql_connect ($dbhost, $dbuser, $dbpass);
mysql_select_db($db);
require("../../core/core_functions.php");
require("../../admin/admin_functions.php");
defineHOST();
$host = HOST;
protect();

$file = safeText(g('file'));
$folder = safeText(g('folder'));
$url = "../../upload/zc_imagegallery/{$folder}/{$file}";
if(isRights(1,2))
{
	if(file_exists($url))
	{
		unlink($url);
	}
	else
	{
		echo "Filen kunde inte hittas\\n{$url}";
	}
}
?>
";

if(string != "")
{
	alert("Message from autoupdatescript: " + string);
}