<?PHP
// Pluginfunktioner i adminpanelen

function zc_add_menu($id, $title, $onlyadminaccess = true)
{
	global $menus;
	$new_menu = array("" . $id => new Menu($id, $title, $onlyadminaccess));
	if(array_key_exists($id, $menus))
	{
		return false;
	}
	else
	{
		$menus = array_merge($new_menu, $menus);
		return true;
	}
	
}

function zc_add_menulink($menu, $title, $link, $locallink = true)
{
	global $menus;
	switch($menu)
	{
		case "publish_menu":
		case "design_menu":
		case "settings_menu":
		case "plugin_menu":
			$action = "add_{$menu}";
			$action($title, $link);
			break;
		default:
			if(array_key_exists($menu, $menus))
			{
				$menuObj = $menus[$menu];
				$menuObj->addLink($title, $link, $locallink);
			}
			else
			{
				return false;
			}
	}
	return true;
}


function zc_del_menulink($menu, $title)
{
	global $menus;
	global $publish_menu;
	global $design_menu;
	global $settings_menu;
	global $plugin_menu;
	switch($menu)
	{
		case "publish_menu":
		case "design_menu":
		case "settings_menu":
		case "plugin_menu":
			$i = 0;
			foreach($$menu->links as $linkrow)
			{
				if($linkrow->title == $title)
				{
					unset($$menu->links[$i]);
					break;
				}
				$i++;
			}
			break;
		default:
			return false;
	}
	return true;
}

?>