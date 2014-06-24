<?PHP 
date_default_timezone_set("Europe/Stockholm");

require("../zc_settings.php");
require("autoloadClasses.php");
session_start();
$objConn = mysql_connect ($dbhost, $dbuser, $dbpass);
mysql_select_db($db);
require("../core/core_functions.php");
require("admin_functions.php");
defineHOST();
$host = HOST;
protect();
defineMenuFile();
require(MENUFILE);
defineOptAndThisPage('admin');
defineLanguage();
if(is_admin())
{
	checkForUpdate();
}
require("getpage.php");
include("../core/language/" . LANGUAGE . ".php");
require("fileIcons.php");
if(isset($_SESSION["this_page"]))
{
	$_SESSION['last_page'] = $_SESSION['this_page'];
	$_SESSION['this_page'] = THIS_PAGE;
}
else
{
	$_SESSION['last_page'] = THIS_PAGE;
	$_SESSION['this_page'] = THIS_PAGE;
}
if(isset($_SESSION['link_back']))
{
	$linkback = $_SESSION['link_back'];
	unset($_SESSION['link_back']);
}

$menus = array();
require("plugin_functions.php");
loadPlugins("admin");
loadThemePlugin("admin");

require("header.php");
require("menu.php");
require("content.php");
require("footer.php");
?>