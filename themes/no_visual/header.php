<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?PHP zc_title(); ?> - <?PHP zc_page_title(); ?> - <?PHP echo zc_desc(); ?></title>
<?PHP
zc_head();
?>
<script type="text/javascript" src="<?PHP echo theme_url(); ?>script/script.js"></script>
<link href="<?PHP theme_url(); ?>style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?PHP
zc_action_control('bodytop');
?>
<div id="main">
<div id="maincontent">
	<?PHP
    zc_action_control('beforetop');
    ?>
    <div id="topbar">
        
    </div> <!-- END topbar -->
	<?PHP
    zc_action_control('aftertop');
    ?>
	<?PHP
    zc_action_control('beforemenu');
    ?>
    <div id="menubar">
		<?PHP
        zc_menu();
        ?>
    </div> <!-- END menubar -->    
	<?PHP
    zc_action_control('aftermenu');
    ?>
