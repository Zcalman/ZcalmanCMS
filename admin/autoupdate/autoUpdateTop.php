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
require("../admin_functions.php");
defineHOST();
$host = HOST;
protect();
defineLanguage();
include("../../core/language/" . LANGUAGE . ".php");
?>