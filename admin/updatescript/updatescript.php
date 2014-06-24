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
rightsToSee(1);
require("updateserver.php");
require("update_functions.php");
$tot_errors = 0;
$this_version = (float)$cms_version;
$vf_file = fopen($erl_versions_file, "r");
while(!feof($vf_file))
{
	$read_version = (float)fgets($vf_file);
	if($this_version < $read_version)
	{
		update_log("#######################################################");
		update_log(" Update to version {$read_version} started " . date("Y-m-d H:i:s"));
		update_log("#######################################################");
		$result = run_update($read_version);
		$tot_errors .= $result;
	}
}
fclose($vf_file);
?>
";

if(string != "")
{
	alert("Message from updatescript: " + string);
}

update_finished(<?PHP echo $tot_errors; ?>);