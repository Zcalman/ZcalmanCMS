<?PHP
// Awesome code goes here

// Ställer in tidszon
date_default_timezone_set("Europe/Stockholm");
header( 'Content-Type:text/xml; charset=utf-8' );

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();


require("../../zc_settings.php");
$objConn = mysql_connect ($dbhost, $dbuser, $dbpass);
mysql_select_db($db);
require("../../core/core_functions.php");
require("../../admin/admin_functions.php");
defineHOST();
$host = HOST;
protect();
defineLanguage();
include("../../core/language/" . LANGUAGE . ".php");

$dir = "../../upload/zc_imagegallery/";

echo "<?xml version=\"1.0\"?> \n";
echo "<root>\n";
if (is_dir($dir))
{
	$dirs = glob($dir . "*");
	foreach($dirs as $d)
	{
		if(is_dir($d))
		{
			
			$dirname = substr(strrchr($d, "/"), 1);
			echo "\t<dir>\n";
			echo "\t\t<name>" . zc_imagegallery_getGalleryName($dirname) . "</name>\n";
			echo "\t\t<folder>{$dirname}</folder>\n";
			echo "\t</dir>\n";
		}
	}
}
echo '</root>';

function zc_imagegallery_getGalleryName($folder)
{
	$file = "../../upload/zc_imagegallery/{$folder}/name.txt";
	$f = fopen($file,'r');
	$line = fgets($f);
	fclose($f);
	return $line;
}
?>
