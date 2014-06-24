<?PHP 
date_default_timezone_set("Europe/Stockholm");

require("../../zc_settings.php");
require("../autoloadClasses.php");
session_start();
$objConn = mysql_connect ($dbhost, $dbuser, $dbpass);
mysql_select_db($db);
require("../../core/core_functions.php");
require("../admin_functions.php");
defineHOST();
$host = HOST;
defineLanguage();
$page_title = get_setting("pagetitle");
include("../../core/language/" . LANGUAGE . ".php");
define("THIS_PAGE", $host . "admin/sendmail/lostpw.php");

// Sätter typ av radbrytning, används i bl.a. headers i mail
if(strtoupper(substr(PHP_OS,0,3)=='WIN')) 
{ 
  $eol = "\r\n"; 
}
elseif (strtoupper(substr(PHP_OS,0,3)=='MAC'))
{ 
  $eol = "\r"; 
}
else
{ 
  $eol = "\n"; 
}

$error = "";
$sendmail = false;

if(isset($_GET['option1']))
{
	define("OPT", $_GET['option1']);
	if(OPT == "sendmail")
	{
		if(isset($_POST['email']))
		{
			$tempemail = mysql_real_escape_string($_POST['email']);
			$sql = mysql_query("SELECT * FROM userbase WHERE email = '{$tempemail}'");
			if(mysql_num_rows($sql) == 1)
			{
				$r = mysql_fetch_assoc($sql);
				$usermail = $r['email'];
				$username = $r['username'];
				$userid = $r['id'];
				$realname = $r['name'];
				$sendmail = true;
			}
			else
			{
				$error = "Kunde inte hitta dig i databasen.";
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
<link href="<?PHP adminhost(); ?>stylesheets/custom-theme/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css" />
<link href="<?PHP adminhost(); ?>stylesheets/style.css" rel="stylesheet" type="text/css" />
<link href="<?PHP adminhost(); ?>stylesheets/lostpw.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="<?PHP adminhost(); ?>images/icons/favicon.ico" type="image/x-icon">
<link rel='shortcut icon' href='<?PHP adminhost(); ?>images/icons/favicon.ico' type='image/x-icon'/ >
<script type="text/javascript">
var host = "<?PHP host(); ?>";
var adminhost = "<?PHP adminhost(); ?>";
var changed = false;
</script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?PHP adminhost(); ?>script/script.js"></script>
<script type="text/javascript" src="<?PHP adminhost(); ?>script/lostpwscript.js"></script>
</head>

<body>
<?PHP
if($sendmail)
{
	$new_pw = generateString(8);
	$new_pw_md5 = md5($new_pw);
	$key = generateString(32);
	$time = date("YmdHis", mktime(date("H")+24));
	$link = HOST . "admin/login/activatepassword/" . $key;
	$sql = mysql_query("UPDATE userbase SET temp_password = '{$new_pw_md5}', temp_password_date = {$time}, temp_password_key = '{$key}' WHERE id = {$userid}");
	
	$to = $r['email'];
	$from = $server_mail;
	$subject = "Nytt lösenord";
	$srvname = $page_title . " - Support";
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/plain; charset=utf-8\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit\r\n";
	$headers .= "From: $srvname <{$from}> \r\n"; 
	$headers .= "Reply-To: Support <{$support_mail}> \r\n"; 
	$text = "Hej {$realname} ({$username})!

Du har fått detta mejl eftersom du har glömt ditt lösenord
på sidan " . HOST . ". 
Känner du inte igen detta eller det inte är
du som har bett om att få ett lösenord kan du strunta i detta mejl.

För att aktivera ditt nya lösenord klicka på länken eller
kopiera den och klistra in i din webbläsare.

OBS!
Länken är endast giltig i 24 timmar.

LÄNK:
{$link}

ANVÄNDARNAMN: {$username}

NYTT LÖSENORD: " .$new_pw."

När du har aktiverat ditt nya lösenord och loggat in med det
kan du självklart byta till ett nytt genom att gå in på inställningar.

Tänk på att inte lämna ut dessa uppgifter till någon annan.

Skulle problem uppstå.
Kontakta webbmaster på {$support_mail}

";

	mail($to, $subject, $text ,$headers);
	
	$error = "Ditt nya l&ouml;senord har nu skickats till dig.<br />D&auml;r finns instruktioner om hur du ska g&aring; till v&auml;ga.<br />Det kan ta n&aring;gra minuter innan mailet kommer fram.";
}
?>
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
            Glömt uppgifter
        </div>
        <div id="loginexitbutton"></div>
        <div id="logincontent">
        	<span>Fyll i din e-postadress så skickas ditt användarnamn och ett nytt lösenord till dig.</span>
            <form action="<?PHP adminhost(); ?>lostpw/sendmail" method="post">
                <input type="text" name="email" onfocus='javascript:falt(this,this.value,"E-postadress" );' onblur='javascript:tom(this,this.value,"E-postadress");' value="E-postadress" class="textfield" />
                <input type="submit" value="Skicka" id="loginbutton" class="submitbutton" />
            </form>
        </div>
    </div>
    <div id="loginfooter">
    </div>
</div>
<?PHP include("../dialogs.php"); ?>
</body>
</html>
