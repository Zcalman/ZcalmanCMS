<?PHP
function adminhost()
{
	if(!defined("HOST"))
	{
		defineHOST();
	}
	echo HOST . "admin/";
	return true;
}

function get_adminhost()
{
	if(!defined("HOST"))
	{
		defineHOST();
	}
	return HOST . "admin/";
}

function print_menu($value, $key)
{
	echo "<li><a onclick='javascript: goto(\"". get_adminhost() . $value->link ."\")'>{$value->title}</a></li>";
}

function print_extramenu($value, $key)
{
	if($value->locallink)
	{
		echo "<li><a onclick='javascript: goto(\"". get_adminhost() . $value->link ."\")'>{$value->title}</a></li>";
	}
	else
	{
		echo "<li><a onclick='javascript: gotonew(\"". $value->link ."\",\"" . $value->title . "\")'>{$value->title}</a></li>";
	}
}

function defineMenuFile()
{
	if($_SESSION['userclass'] == 1)
	{
		$menu = "admin";
	}
	elseif($_SESSION['userclass'] == 2)
	{
		$menu = "writer";
	}
	define("MENUFILE", "menus/" . $menu . "menu.php");
}

function add_publish_menu($title, $page, $localpage = true)
{
	global $publish_menu;
	$publish_menu->addlink($title, $page, $localpage);
}

function add_design_menu($title, $page, $localpage = true)
{
	global $design_menu;
	$design_menu->addlink($title, $page, $localpage);
}

function add_settings_menu($title, $page, $localpage = true)
{
	global $settings_menu;
	$settings_menu->addlink($title, $page, $localpage);
}

function add_plugin_menu($title, $page, $localpage = true)
{
	global $plugin_menu;
	$plugin_menu->addlink($title, $page, $localpage);
}

function isOnline()
{
	if(isset($_SESSION['is_online']) && $_SESSION['is_online'] == true)
	return true;
	else
	return false;
}

function isRights($int, $int2 = NULL)
{
	if($_SESSION['userclass'] == $int || $_SESSION['userclass'] == $int2)
	return true;
	else
	return false;
}

function rightsToSee($int, $int2 = 0)
{
	if(!isRights($int) && !isRights($int2))
	{
		adminerror(403);
	}
}

function protect()
{
	if(!isOnline())
	{
		header("Location: " .HOST . "admin/login");
		exit();
		die();
	}
}

function adminerror($nr)
{
	$_SESSION['error_return_page'] = $_SESSION['last_page'];
	go(HOST. "admin/error/". $nr);
	exit();
	die();
}

function removeDir($dir)
{ 
	if (is_dir($dir)) 
	{ 
		$objects = scandir($dir); 
		foreach ($objects as $object) 
		{ 
			if ($object != "." && $object != "..")
			{ 
				if (filetype($dir."/".$object) == "dir")
				{
					removeDir($dir."/".$object);
				}
				else
				{
					unlink($dir."/".$object);
				}
			} 
		} 
		reset($objects); 
		rmdir($dir); 
	} 
}
function loadPluginInfo($slug)
{
	$plugininfofile = "../plugins/{$slug}/plugin_info.php";
	$pluginfile = "../plugins/{$slug}/plugin.php";
	if(file_exists($plugininfofile) && file_exists($pluginfile))
	{
		include($plugininfofile);
		if($plugin_slug != $slug)
		{
			return false;							// Kontroll så slug är samma som mappnamnet.
		}
		return array("slug" => $plugin_slug, "name" => $plugin_name, "version" => $plugin_version, "creator" => $plugin_creator, "url" => $plugin_url, "description" => $plugin_desc);
	}
	else
	return false;
}
function checkForUpdate()
{
	require("updatescript/updateserver.php");
	global $cms_version;
	$need_update = false;
	$vf = fopen($version_file, "r");
	$current_version = (float)fgets($vf);
	if($cms_version < $current_version)
	{
		$need_update = true;
	}
	fclose($vf);
	$_SESSION['need_update'] = $need_update;
	return $need_update;
}














?>