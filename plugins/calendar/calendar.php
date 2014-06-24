<?PHP
date_default_timezone_set("Europe/Stockholm");

if(file_exists("../../zc_settings.php"))
{
	require_once("../../zc_settings.php");
}
else
{
	runFirstTimeSetup();
}
session_start();
$objConn = mysql_connect ($dbhost, $dbuser, $dbpass);
mysql_select_db($db);
require_once("../../core/core_functions.php");
require_once("../../core/plugin_functions.php");
require_once("../../core/theme_functions.php");
include("../../core/classes/Mobile_Detect.class.php");
defineHOST();
$host = HOST;
defineOptAndThisPage();
defineLanguage();
defineTheme();
require_once("../../core/language/" . LANGUAGE . ".php");


// Kalenderscript
require("Calendar.class.php");
require("CalDay.class.php");
if(defined("OPT1") && defined("OPT2"))
{
	$year = date("Y", mktime(0,0,0,(int)OPT2,1,(int)OPT1));
	$month = date("n", mktime(0,0,0,(int)OPT2,1,(int)OPT1));
}
else
{
	$year = date("Y");
	$month = date("n");
}
$cal = new Calendar($month, $year);

$query = mysql_query("SELECT * FROM zc_calendar WHERE month = {$month} AND year = {$year}");
while($r = mysql_fetch_assoc($query))
{
	$cal->addNote($r['day']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Calendar</title>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> 
<script type="text/javascript" src="<?PHP theme_url(); ?>script/script.js"></script>
<script type="text/javascript" src="<?PHP host(); ?>plugins/calendar/calendar.script.js"></script>
<script type="text/javascript">
	var host = "<?PHP host(); ?>";
</script>
<link href="<?PHP theme_url(); ?>calendar.style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="mainContent">
<div class="monthDisplay">
    <div class="arrow_left" onclick='javascript: goto("<?PHP host(); ?>plugins/calendar/calendar.php?option1=<?PHP echo $cal->preyear; ?>&option2=<?PHP echo $cal->premonth; ?>");'>&lt;</div>
    <div class="monthLabel"><?PHP echo $monthName[$month] . " " . $year; ?></div>
    <div class="arrow_right" onclick='javascript: goto("<?PHP host(); ?>plugins/calendar/calendar.php?option1=<?PHP echo $cal->nextyear; ?>&option2=<?PHP echo $cal->nextmonth; ?>");'>&gt;</div>
</div>
<?PHP
$cal->printCal();
?>
<div id="OndayAct">
</div>
</div>
</body>
</html>