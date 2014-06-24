<?PHP
// Ett simpelt plugin

### Körs vid installation
function sections_install()
{
	set_setting("use_sectionstart_default", "false");
	$sql = mysql_query("CREATE TABLE `sections_menu` (`id` int(11) NOT NULL AUTO_INCREMENT , `title` text NOT NULL , `link` text NOT NULL , `parent` int(11) NOT NULL DEFAULT '0', `sort` int(11) NOT NULL DEFAULT '99', `target` text NOT NULL , `submenu` tinyint(1) NOT NULL , `active` tinyint(1) NOT NULL DEFAULT '1', `section_id` int(11) NOT NULL , UNIQUE KEY `id` (`id`)) ENGINE = MyISAM DEFAULT CHARSET = latin1;");
	$sql = mysql_query("CREATE TABLE `sections_news` (`id` int(11) NOT NULL AUTO_INCREMENT , `title` text NOT NULL , `text` MEDIUMTEXT NOT NULL , `time` text NOT NULL , `date` text NOT NULL , `slug` text NOT NULL , `section_id` int(11) NOT NULL , UNIQUE KEY `id` (`id`)) ENGINE = MyISAM DEFAULT CHARSET = latin1;");
	$sql = mysql_query("CREATE TABLE `sections_pages` (`id` int(11) NOT NULL AUTO_INCREMENT , `slug` text NOT NULL , `title` text NOT NULL , `text` MEDIUMTEXT NOT NULL , `link` text NOT NULL , `type` text NOT NULL , `startx` int(11) NOT NULL , `starty` int(11) NOT NULL , `form` int(11) NOT NULL DEFAULT '0', `extrawide` tinyint(1) NOT NULL DEFAULT '0', `section_id` int(11) NOT NULL , UNIQUE KEY `id` (`id`)) ENGINE = MyISAM DEFAULT CHARSET = latin1;");
	$sql = mysql_query("CREATE TABLE `sections_userrights` (`id` int(11) NOT NULL AUTO_INCREMENT , `userid` int(11) NOT NULL , `section_id` int(11) NOT NULL , UNIQUE KEY `id` (`id`)) ENGINE = MyISAM DEFAULT CHARSET = latin1;");
	$sql = mysql_query("CREATE TABLE `sections` (`id` int(11) NOT NULL AUTO_INCREMENT , `name` text NOT NULL , `slug` text NOT NULL , UNIQUE KEY `id` (`id`)) ENGINE = MyISAM DEFAULT CHARSET = latin1;");
}

### Körs vid avinstallation
function sections_uninstall()
{
	$sql = mysql_query("DROP TABLE `sections`");
	$sql = mysql_query("DROP TABLE `sections_news`");
	$sql = mysql_query("DROP TABLE `sections_pages`");
	$sql = mysql_query("DROP TABLE `sections_menu`");
	$sql = mysql_query("DROP TABLE `sections_userrights`");
	$sql = mysql_query("DELETE FROM `settings` WHERE name = 'use_sectionstart_default'");
	$sql = mysql_query("DELETE FROM `settings` WHERE name = 'default_section'");
	$sql = mysql_query("OPTIMIZE TABLE `settings`");
}

### Körs vid aktivering
function sections_activate()
{
	
}

### Körs vid avaktivering
function sections_inactivate()
{
	
}

### Körs på varje sida på hemsidan när pluginet läses in
function sections_init_page()
{
	$sql = mysql_query("SELECT * FROM sections");
	while($r = mysql_fetch_assoc($sql))
	{
		zc_add_page($r['slug'], "sectionspagehandler.php", $r['name']);
	}
	zc_add_action('aftergetpage', "sections_defineSection");
	zc_add_action('aftergetpage', "sections_startpage");
}

### Körs på varje sida i administrationspanelen när pluginet läses in
function sections_init_admin()
{
	if(is_admin())
	{
		zc_del_menulink("publish_menu", "Sidor");
		zc_del_menulink("publish_menu", "Nyheter");
		zc_del_menulink("design_menu", "Meny");
		zc_add_menulink("design_menu", "Huvudmeny", "menus");
		zc_add_menulink("publish_menu", "Sidor", "plugin/sections/sectionpages");
		zc_add_menulink("publish_menu", "Resultatsidor", "plugin/sections/sectionrespages");
		zc_add_menulink("publish_menu", "Nyheter", "plugin/sections/sectionnews");
		zc_add_menulink("design_menu", "Meny", "plugin/sections/menus");
		zc_add_menu("section_menu", "Sektioner");
		zc_add_menulink("section_menu", "Hantera sektioner", "plugin/sections");
		zc_add_menulink("section_menu", "Inställningar", "plugin/sections/settings");
		zc_add_menulink("section_menu", "Rättigheter", "plugin/sections/userrights");
	}
	elseif(is_writer())
	{
		$temp = array();
		$sql = mysql_query("SELECT section_id FROM sections_userrights WHERE userid = {$_SESSION['userid']}");
		while($r = mysql_fetch_assoc($sql))
		{
			array_push($temp, $r['section_id']);
		}
		$_SESSION['sections_rights'] = $temp;
		if(count($temp) > 0)
		{
			zc_del_menulink("publish_menu", "Nyheter");
			zc_add_menulink("publish_menu", "Sidor", "plugin/sections/sectionpages");
			zc_add_menulink("publish_menu", "Resultatsidor", "plugin/sections/sectionrespages");
			zc_add_menulink("publish_menu", "Nyheter", "plugin/sections/sectionnews");
			zc_add_menulink("design_menu", "Meny", "plugin/sections/menus");
		}
		else
		{
			zc_del_menulink("publish_menu", "Nyheter");
		}
	}
}

### Körs när man går in på pluginet i adminpanelen (http://website.com/admin/plugin/yourplugin)
function sections_show_adminpage()
{
	$page = "start";
	if(defined("OPT2"))
	{
		$page = safeText(OPT2);
	}
	
	echo '<script type="text/javascript" src="' . HOST . 'plugins/sections/pages/admin/script.js"></script>';
	
	switch($page)
	{
		case "start":
			include("../plugins/sections/pages/admin/sections.php");
		break;
		case "section":
			include("../plugins/sections/pages/admin/section.php");
		break;
		case "sectionpages":
			include("../plugins/sections/pages/admin/pages.php");
		break;
		case "sectionpage":
			include("../plugins/sections/pages/admin/page.php");
		break;
		case "sectionrespages":
			include("../plugins/sections/pages/admin/res_pages.php");
		break;
		case "sectionrespage":
			include("../plugins/sections/pages/admin/res_page.php");
		break;
		case "sectionnews":
			include("../plugins/sections/pages/admin/news.php");
		break;
		case "sectionnew":
			include("../plugins/sections/pages/admin/new.php");
		break;
		case "menus":
			include("../plugins/sections/pages/admin/menus.php");
		break;
		case "menu":
			include("../plugins/sections/pages/admin/menu.php");
		break;
		case "save":
			include("../plugins/sections/pages/admin/save.php");
		break;
		case "delete":
			include("../plugins/sections/pages/admin/delete.php");
		break;
		case "settings":
			include("../plugins/sections/pages/admin/settings.php");
		break;
		case "userrights":
			include("../plugins/sections/pages/admin/userrights.php");
		break;
		default:
			adminerror(404);
	}
}

### Kollar om en tabell finns i databasen
function sections_table_exists($tablename, $database = false)
{

    if(!$database) 
	{
        $res = mysql_query("SELECT DATABASE()");
        $database = mysql_result($res, 0);
    }

    $res = mysql_query("
        SELECT COUNT(*) AS count 
        FROM information_schema.tables 
        WHERE table_schema = '$database' 
        AND table_name = '$tablename'
    ");

    return mysql_result($res, 0) == 1;
}

function sections_getSectionName($id)
{
	$sql = mysql_query("SELECT name FROM sections WHERE id = {$id}");
	$r = mysql_fetch_assoc($sql);
	return $r['name'];
}

function sections_getSectionSlug($id)
{
	$sql = mysql_query("SELECT slug FROM sections WHERE id = {$id}");
	$r = mysql_fetch_assoc($sql);
	return $r['slug'];
}
function sections_menu()
{
	global $pagevars;
	echo "<ul id=\"sections_menu\">";
	echo "<li style=\"border:0px !important;\"><a href=\"" . HOST . SECTION_SLUG . "/\" target=\"_self\" title=\"Startsidan\">Startsidan</a></li>";
	$sql = mysql_query("SELECT * FROM sections_menu WHERE section_id = ". SECTION_ID ." AND parent = 0 AND active = 1 ORDER BY `sort` ASC");
	while($r = mysql_fetch_assoc($sql))
    {
        $query = mysql_query("SELECT * FROM sections_menu WHERE section_id = ". SECTION_ID ." AND parent = {$r['id']} AND active = 1 ORDER BY `sort` ASC");
		if($r['link'] != "#")
		{
        	echo "<li><a href=\"" . $r['link'] . "\" target=\"" . $r['target'] . "\" title=\"" . $r['title'] . "\">" . $r['title'] . "</a>";
		}
		else
		{
			echo "<li><a title=\"" . $r['title'] . "\">" . $r['title'] . "</a>";
		}
		if(mysql_num_rows($query) > 0 && $r['submenu'] == 1)
		{
			echo "<span class=\"submenu_arrow\"></span>";
			echo "<ul>";
			while($s = mysql_fetch_assoc($query))
			{
				echo "<li><a href=\"" . $s['link'] . "\" target=\"" . $s['target'] . "\" title=\"" . $s['title'] . "\">" . $s['title'] . "</a></li>";
			}
			echo "</ul>";
		}
		echo "</li>";
    }
    echo "</ul>";
}
function sections_defineSection()
{
	if(defined("PAGE"))
	{
		$sql = mysql_query("SELECT * FROM sections WHERE slug = '". PAGE . "' LIMIT 1");
		if(mysql_num_rows($sql) == 1)
		{
			$section = mysql_fetch_assoc($sql);
		}
		else
		{
			$default_section = get_setting("default_section");
			if($default_section != "No such setting in database")
			{
				$query = mysql_query("SELECT * FROM sections WHERE id = {$default_section}");
				$section = mysql_fetch_assoc($query);
			}
			else
			{
				exit("No default section");
			}
		}
		define("SECTION_ID", $section['id']);
		define("SECTION_NAME", $section['name']);
		define("SECTION_SLUG", $section['slug']);
	}
	else
	{
		exit("No page");
	}
}
function sections_startpage()
{
	global $content;
	if(get_setting("default_section") == SECTION_ID)
	{
		if(PAGE == "start" && get_setting("use_sectionstart_default") == "true")
		{
			$content = "plugins/sections/pages/homepage/start.php";
		}
	}
}
function sections_code_error($nr)
{
	header("Location: " .HOST. SECTION_SLUG . "/error/". $nr);
	exit();
	die();
}

?>