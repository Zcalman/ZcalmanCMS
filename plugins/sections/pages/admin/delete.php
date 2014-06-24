<?PHP
global $host;
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

if(defined("OPT3"))
{
	$type = safeText(OPT3);
	switch($type)
	{
		case "page": 
			if(isRights(1,2))
			{
				if(defined("OPT4"))
				{
					$id = (int)safeText(OPT4);
					$sqlq= mysql_query("SELECT * FROM sections_pages WHERE id = {$id}");
					$r = mysql_fetch_assoc($sqlq);
					if(!is_admin())
					{
						if(!in_array($r['section_id'], $_SESSION['sections_rights']))
						{
							adminerror(403);
						}
					}
					$sql = mysql_query("DELETE FROM sections_pages WHERE id = {$id}");
					$sql = mysql_query("OPTIMIZE TABLE `sections_pages`");
				}
				else
				{
					adminerror(404);
				}
				$ret = $_SESSION['return'];
				unset($_SESSION['return']);
				if($ret == HOST . "admin/plugin/sections/save/page/")
				{
					$ret = HOST . "admin/plugin/sections/sectionpages/". $r['section_id'];
				}
				elseif($ret == HOST . "admin/plugin/sections/save/respage/")
				{
					$ret = HOST . "admin/plugin/sections/sectionrespages/". $r['section_id'];
				}
				go($ret);
				echo $ret;
			}
			else
			{
				adminerror(404);
			}
			break;
		case "menu": 
			if(isRights(1,2))
			{
				$r = "";
				if(defined("OPT4"))
				{
					$id = (int)safeText(OPT4);
					$sqlq= mysql_query("SELECT * FROM sections_menu WHERE id = {$id}");
					$r = mysql_fetch_assoc($sqlq);
					if($r['parent'] == 0)
					{
						$del = mysql_query("DELETE FROM sections_menu WHERE parent = {$id}");
					}
					$sql = mysql_query("DELETE FROM sections_menu WHERE id = {$id}");
					$sql = mysql_query("OPTIMIZE TABLE `sections_menu`");
				}
				else
				{
					adminerror(404);
				}
				$ret = $_SESSION['return'];
				unset($_SESSION['return']);
				if($ret == HOST . "admin/plugin/sections/save/menu/")
				{
					$ret = HOST . "admin/plugin/sections/save/menus/" . $r['section_id'] . "";
				}
				go($ret);
				//echo $ret;
			}
			else
			{
				adminerror(404);
			}
			break;
		case "news": 
			if(isRights(1,2))
			{				
				if(defined("OPT4"))
				{
					$id = (int)safeText(OPT4);
					$sqlq= mysql_query("SELECT * FROM sections_news WHERE id = {$id}");
					$r = mysql_fetch_assoc($sqlq);
					if(!is_admin())
					{
						if(!in_array($r['section_id'], $_SESSION['sections_rights']))
						{
							adminerror(403);
						}
					}
					$sql = mysql_query("DELETE FROM sections_news WHERE id = {$id}");
					$sql = mysql_query("OPTIMIZE TABLE `sections_news`");
				}
				else
				{
					adminerror(404);
				}
				$ret = $_SESSION['return'];
				unset($_SESSION['return']);
				if($ret == HOST . "admin/plugin/sections/save/news/")
				{
					$ret = HOST . "admin/plugin/sections/sectionnews/". $r['section_id'];
				}
				go($ret);
			}
			else
			{
				adminerror(404);
			}
			break;
		case "section":
			if(isRights(1))
			{
				$ret = HOST . "admin/plugin/sections/";
				if(defined("OPT4"))
				{
					$id = (int)safeText(OPT4);
					$sqlq= mysql_query("SELECT * FROM sections WHERE id = {$id}");
					$r = mysql_fetch_assoc($sqlq);
					
					if(get_setting('default_section') == $id)
					{
						adminerror("Du kan inte ta bort den sektion som står inställd som standardsektion");
					}
					
					$sql = mysql_query("DELETE FROM sections_menu WHERE section_id = {$id}");
					$sql = mysql_query("DELETE FROM sections_news WHERE section_id = {$id}");
					$sql = mysql_query("DELETE FROM sections_pages WHERE section_id = {$id}");
					$sql = mysql_query("DELETE FROM sections_userrights WHERE section_id = {$id}");
					$sql = mysql_query("DELETE FROM sections WHERE id = {$id}");
					$sql = mysql_query("OPTIMIZE TABLE `sections_news`");
					$sql = mysql_query("OPTIMIZE TABLE `sections_menu`");
					$sql = mysql_query("OPTIMIZE TABLE `sections_pages`");
					$sql = mysql_query("OPTIMIZE TABLE `sections_userrights`");
					$sql = mysql_query("OPTIMIZE TABLE `sections`");
				}
				go($ret);
			}
			else
			{
				adminerror(403);
			}
			break;
		case "right":
			if(isRights(1))
			{
				if(defined("OPT5"))
				{
					$ret = HOST . "admin/plugin/sections/userrights/" . safeText(OPT5) . "/1/Användaren har tagits bort från sektionen";
				}
				else
				{
					adminerror(404);
				}
				
				if(defined("OPT4"))
				{
					$id = (int)safeText(OPT4);
					$sqlq= mysql_query("SELECT * FROM sections_userrights WHERE id = {$id}");
					$r = mysql_fetch_assoc($sqlq);
					$sql = mysql_query("DELETE FROM sections_userrights WHERE id = {$id}");
					$sql = mysql_query("OPTIMIZE TABLE `sections_userrights`");
				}
				go($ret);
			}
			break;
		default:
			adminerror(404);
	}
}
else
{
	adminerror(404);
}
?>