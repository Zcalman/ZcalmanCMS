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
defineLanguage();
$page_title = get_setting("pagetitle");
include("../core/language/" . LANGUAGE . ".php");

$error = "";
if(isOnline())
{
	header("Location: " .HOST. "admin/");
}	
if(isset($_GET['option1']))
{
	define("OPT", $_GET['option1']);
	if(isset($_GET['option2']))
	{
		define("KEY", $_GET['option2']);
	}
	if(OPT == "submit")
	{
		if(isset($_POST['username']) && isset($_POST['password']))
		{			
			$temppassword = md5($_POST['password']);
			$tempusername = mysql_real_escape_string($_POST['username']);
			$sql = mysql_query("SELECT * FROM userbase WHERE username = '{$tempusername}' AND password = '{$temppassword}'");
			if(mysql_num_rows($sql) == 1)
			{
				$r = mysql_fetch_assoc($sql);
				
				$_SESSION['is_online'] = true;
				$_SESSION['userid'] = $r['id'];
				$_SESSION['username'] = $r['username'];
				$_SESSION['email'] = $r['email'];
				$_SESSION['userclass'] = (int)$r['userclass'];
				$_SESSION['name'] = $r['name'];
				header("Location: " .HOST. "admin/");
				exit();
			}
			else
			{
				$error = "Fel anv&auml;ndarnamn eller l&ouml;senord";
			}
		}
	}
	elseif(OPT == "activatepassword")
	{
		$error = "Ogiltig nyckel!<br />Det kan bero på att<br /> - nyckeln är för gammal.<br />- du begärt ett nytt lösenord efter att detta skapats.<br />- nyckeln är ofullständig.";
		if(defined("KEY"))
		{
			$key = safeText(KEY);
			if(strlen($key) == 32)
			{
				$time = date("YmdHis");
				$sql = mysql_query("SELECT * FROM userbase WHERE temp_password_key = '{$key}'");
				if(mysql_num_rows($sql) == 1)
				{
					$r = mysql_fetch_assoc($sql);
					if($r['temp_password_date'] > $time)
					{
						$query = mysql_query("UPDATE userbase SET password = '{$r['temp_password']}', temp_password = '', temp_password_date = '', temp_password_key = '' WHERE id = {$r['id']}");
						$error = "Aktivering lyckades!<br />Du kan nu logga in med det nya lösenordet.<br />När du har loggat in kan du byta lösenord genom att gå in på inställningar.";
					}
					else
					{
						$query = mysql_query("UPDATE userbase SET temp_password = '', temp_password_date = '', temp_password_key = '' WHERE id = {$r['id']}");
					}
				}
					
			}
		}
	}
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kontrollpanelen - <?PHP echo $page_title; ?></title>
<link href="<?PHP adminhost(); ?>stylesheets/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<link href="<?PHP adminhost(); ?>stylesheets/style.css" rel="stylesheet" type="text/css" />
<link href="<?PHP adminhost(); ?>stylesheets/login.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="<?PHP adminhost(); ?>images/icons/favicon.ico" type="image/x-icon">
<link rel='shortcut icon' href='<?PHP adminhost(); ?>images/icons/favicon.ico' type='image/x-icon'/ >
<script type="text/javascript">
var host = "<?PHP host(); ?>";
var adminhost = "<?PHP adminhost(); ?>";
var changed = false;
</script>
<script type="text/javascript" src="<?PHP adminhost(); ?>script/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="<?PHP adminhost(); ?>script/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?PHP adminhost(); ?>script/script.js"></script>
<script type="text/javascript" src="<?PHP adminhost(); ?>script/loginscript.js"></script>

</head>

<body>
<div id="preload">
<img src="<?PHP adminhost(); ?>images/theme/logout_active.png" />
</div>
<div id="loginmain">
	<?PHP 
    if($error != "")
    {
        echo "<div id='errormessage'>{$error}</div>";
    }
    ?>
    <div id="logintopbar">
    </div>
    <div id="logindiv">
        <div id="titlearrow">
            Logga in
        </div>
        <div id="loginexitbutton"></div>
        <div id="logincontent">
            <form action="<?PHP adminhost(); ?>login/submit" method="post">
                <input type="text" name="username" onfocus='javascript:falt(this,this.value,"Användarnamn" );' onblur='javascript:tom(this,this.value,"Användarnamn");' value="Användarnamn" class="textfield" />
                <input type="password" name="password" onfocus='javascript:falt(this,this.value,"Lösenord" );' onblur='javascript:tom(this,this.value,"Lösenord");' value="Lösenord" class="textfield" />
            	<a href="<?PHP adminhost(); ?>lostpw" id="lostpwlink">Glömt uppgifter</a>
                <input type="submit" value="Logga in" id="loginbutton" class="submitbutton" />
            </form>
        </div>
    </div>
    <div id="loginfooter">
    </div>
</div>
<?PHP include("dialogs.php"); ?>
</body>
</html>
