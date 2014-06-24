<?PHP
require("functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?PHP zc_page_title(); ?> | <?PHP zc_title(); ?> | <?PHP echo zc_desc(); ?></title>
<?PHP
zc_head();
?>
<script type="text/javascript" src="<?PHP echo theme_url(); ?>script/script.js"></script>
<link href="<?PHP theme_url(); ?>style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="background">
	<img src="<?PHP theme_url(); ?>images/background-resized.jpg" />
</div>
<div id="main">
<div id="maincontent">
    <div id="topbar">
        <img class="titleImg" src="<?PHP theme_url(); ?>images/lucy-wildheart.png" />
        <img class="profileImg" src="<?PHP theme_url(); ?>images/standing-web.jpg" />
    </div> <!-- END topbar -->
    <div id="menybar">
		<?PHP
        zc_menu();
        ?>
        
    </div> <!-- END menybar -->
	
    <div id="content">
    	<div class="partnerbar">
        </div>
        <div class="pagecontent">