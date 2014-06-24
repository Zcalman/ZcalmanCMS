<?PHP 
date_default_timezone_set("Europe/Stockholm");

if(file_exists("zc_settings.php"))
{
	require_once("zc_settings.php");
}
else
{
	runFirstTimeSetup();
}
require_once("core/autoloadClasses.php");
session_start();
$objConn = mysql_connect ($dbhost, $dbuser, $dbpass);
mysql_select_db($db);
require_once("core/core_functions.php");
require_once("core/plugin_functions.php");
require_once("core/theme_functions.php");
require_once("core/form_functions.php");
defineHOST();
$host = HOST;
defineOptAndThisPage();
defineLanguage();
defineTheme();

if(isset($_SESSION['link_back']))
{
	$linkback = $_SESSION['link_back'];
	unset($_SESSION['link_back']);
}

$pluginpages = array();
$actionareas = array();
$pluginmeta = NULL;
zc_add_action('bodybottom', "checkForMobile");
loadPlugins("page");
loadThemePlugin("page");
require_once("core/getpage.php");
require_once("core/language/" . LANGUAGE . ".php");
zc_action_control('aftergetpage');

if(PAGE == "news")
{
	$newsfeed = new NewsFeed();	
}
if(PAGE == "article")
{
	$article = new Article();	
}
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

loadPage();
?>