<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Kontrollpanel - <?PHP echo $page_title; ?></title>
<link href="<?PHP adminhost(); ?>stylesheets/custom-theme/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css" />
<link href="<?PHP adminhost(); ?>stylesheets/style.css" rel="stylesheet" type="text/css" />
<link href="<?PHP adminhost(); ?>stylesheets/calendar.style.css" rel="stylesheet" type="text/css" />
<link href="<?PHP adminhost(); ?>stylesheets/table.style.css" rel="stylesheet" type="text/css" />
<link href="<?PHP adminhost(); ?>stylesheets/pagenav.style.css" rel="stylesheet" type="text/css" />
<link href="<?PHP adminhost(); ?>stylesheets/RTEditor.style.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="<?PHP adminhost(); ?>images/icons/favicon.ico" type="image/x-icon">
<link rel='shortcut icon' href='<?PHP adminhost(); ?>images/icons/favicon.ico' type='image/x-icon' />
<script type="text/javascript">
var host = "<?PHP host(); ?>";
var adminhost = "<?PHP adminhost(); ?>";
var page = "<?PHP echo $page; ?>";
var changed = false;
</script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?PHP adminhost(); ?>script/script.js"></script>
</head>

<body>
<div id="preload">
<?PHP include("preloads.php"); ?>
</div>
<?PHP
if($_SESSION['need_update'] && is_admin())
{
	?>
	<div id="top-messagebox">
    		Det finns en ny rekommenderad uppdatering för Zimba CMS. <a href="<?PHP adminhost(); ?>update">Klicka här</a> för att komma till uppdateringssidan.
    </div>
    <?PHP
}
?>
<div id="main">
    <div id="topbar">
        <div id="topright" onclick='javascript: goto("<?PHP adminhost(); ?>");'>
            Kontrollpanelen
        </div>
        <div id="logoutbutton"></div>
    </div>
	<div id="maincontent">
