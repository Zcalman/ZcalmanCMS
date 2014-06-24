<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

if(defined("OPT1"))
{
	$type = safeText(OPT1);
	switch($type)
	{
		case "user": 
			if(isRights(1))
			{
				if(defined("OPT2"))
				{
					$id = (int)safeText(OPT2);
					$sqlq= mysql_query("SELECT * FROM userbase WHERE id = {$id}");
					$r = mysql_fetch_assoc($sqlq);
					$sql = mysql_query("DELETE FROM userbase WHERE id = {$id}");
				}
				$ret = $_SESSION['return'];
				unset($_SESSION['return']);
				if($ret == $host . "admin/save/user/")
				{
					$ret = $host . "admin/users";
				}
				go($ret);
				echo $ret;
			}
			else
			{
				adminerror(404);
			}
			break;
		case "page": 
			if(isRights(1))
			{
				if(defined("OPT2"))
				{
					$id = (int)safeText(OPT2);
					$sql = mysql_query("DELETE FROM pages WHERE id = {$id}");
				}
				$ret = $_SESSION['return'];
				unset($_SESSION['return']);
				if($ret == $host . "admin/save/page/")
				{
					$ret = $host . "admin/pages";
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
			if(isRights(1))
			{
				if(defined("OPT2"))
				{
					$id = (int)safeText(OPT2);
					$sqlq= mysql_query("SELECT * FROM menu WHERE id = {$id}");
					$r = mysql_fetch_assoc($sqlq);
					if($r['parent'] == 0)
					{
						$del = mysql_query("DELETE FROM menu WHERE parent = {$id}");
					}
					$sql = mysql_query("DELETE FROM menu WHERE id = {$id}");
				}
				$ret = $_SESSION['return'];
				unset($_SESSION['return']);
				if($ret == $host . "admin/save/menu/")
				{
					$ret = $host . "admin/menus";
				}
				go($ret);
				echo $ret;
			}
			else
			{
				adminerror(404);
			}
		break;
		case "news": 
			if(isRights(1,2))
			{
				if(defined("OPT2"))
				{
					$id = (int)safeText(OPT2);
					$sqlq= mysql_query("SELECT * FROM news WHERE id = {$id}");
					$r = mysql_fetch_assoc($sqlq);
					$sql = mysql_query("DELETE FROM news WHERE id = {$id}");
				}
				$ret = $_SESSION['return'];
				unset($_SESSION['return']);
				if($ret == $host . "admin/save/news/")
				{
					$ret = $host . "admin/news";
				}
				go($ret);
			}
			else
			{
				adminerror(404);
			}
		break;
		case "theme":
			if(isRights(1))
			{
				$ret = HOST . "admin/themes/Temat har tagits bort!";
				if(defined("OPT2"))
				{
					$id = safeText(OPT2);
					$activetheme = get_setting("theme");
					if($id != $activetheme)
					{
						removeDir("../themes/" . $id);
					}
					else
					{
						$ret = HOST . "admin/themes/Du kan inte ta bort temat som används!";
					}
				}
				go($ret);
			}
			else
			{
				adminerror(404);
			}
			break;
		case "form":
			if(isRights(1))
			{
				if(defined("OPT2"))
				{
					$id = (int)safeText(OPT2);
					$sqlq= mysql_query("SELECT * FROM `forms` WHERE `id` = {$id}");
					$r = mysql_fetch_assoc($sqlq);
					$sql = mysql_query("DELETE FROM `forms` WHERE `id` = {$id}");
					$sqlForms = mysql_query("DELETE FROM `forms_objects` WHERE `formid` = {$id}");
					$sqlDelPageForm = mysql_query("UPDATE `pages` SET `form` = 0 WHERE `form` = {$id}");
					if(hasPlugin('sections')) {
						$sqlDelSecPageForm = mysql_query("UPDATE `sections_pages` SET `form` = 0 WHERE `form` = {$id}");
					}
				}
				$ret = $_SESSION['return'];
				unset($_SESSION['return']);
				if($ret == $host . "admin/save/form/". $id)
				{
					$ret = $host . "admin/forms";
				}
				go($ret);
			}
			else
			{
				adminerror(404);
			}
		break;
		case "form_result": 
			if(isRights(1))
			{
				if(defined("OPT2"))
				{
					$id = (int)safeText(OPT2);
					$sqlq= mysql_query("SELECT * FROM `forms_results` WHERE `id` = {$id}");
					$r = mysql_fetch_assoc($sqlq);
					$sql = mysql_query("DELETE FROM `forms_results` WHERE `id` = {$id}");
				}
				$ret = $_SESSION['return'];
				unset($_SESSION['return']);
				go($ret);
			}
			else
			{
				adminerror(404);
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