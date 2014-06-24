<?PHP
global $host;
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

if(defined("OPT3"))
{
	if(OPT3 == "page")
	{
		if(isset($_POST['pageid']))
		{
			$id = safeText($_POST['pageid']);
			$section_id = safeText($_POST['sectionid']);					
			$title = safeText($_POST['title']);
			$slug = safeText($_POST['slug']);
			$type = safeText($_POST['type']);
			$html = safeText($_POST['htmltext'], true);
			$link = safeText($_POST['iframelink']);
			$startx = (int)safeText($_POST['startx']);
			$starty = (int)safeText($_POST['starty']);
			$formid = (int)safeText($_POST['formid']);
			
			if(!is_admin())
			{
				if(!in_array($section_id, $_SESSION['sections_rights']))
				{
					adminerror(403);
				}
			}
					
			if($id == "" || $section_id == "" || $title == "" || $slug == "" || $type == "" || ($type == "iframe" && $link == ""))
			{
				adminerror("Något av de obligatoriska fälten är tomt!");
			}
			
			if($id == "Ny sida")
			{
				$query = mysql_query("SELECT * FROM sections_pages WHERE slug = '{$slug}' AND section_id = {$section_id}");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("En sida med den länken finns redan.");
				}
				else
				{
					$sql = mysql_query("INSERT INTO sections_pages (slug,title,text,link,type,startx,starty,section_id,form) VALUES ('{$slug}', '{$title}', '{$html}', '{$link}', '{$type}', {$startx}, {$starty}, {$section_id}, {$formid})");
				}
				$query = mysql_query("SELECT * FROM sections_pages WHERE slug = '{$slug}' AND section_id = {$section_id} AND title = '{$title}' AND type = '{$type}'");
			}
			else
			{
				$query = mysql_query("SELECT * FROM sections_pages WHERE id = {$id}");
				$p = mysql_fetch_assoc($query);
				$sql = mysql_query("SELECT slug FROM sections WHERE id = {$section_id}");
				$s = mysql_fetch_assoc($sql);
				$oldlink = HOST . $s['slug'] . "/" . $p['slug'];
				$newlink = HOST . $s['slug'] . "/" . $slug;
				
				$query = mysql_query("SELECT * FROM sections_pages WHERE slug = '{$slug}' AND section_id = {$section_id} AND id != {$id}");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("En sida med den länken finns redan.");
				}
				else
				{
					$sql = mysql_query("UPDATE sections_pages SET slug = '{$slug}', title = '{$title}', text = '{$html}', link = '{$link}', type = '{$type}', startx = {$startx}, starty = {$starty}, form = {$formid} WHERE id = {$id}")or exit(mysql_error());
				}
				
				if(!sameValue($oldlink, $newlink))
				{
					$sql = mysql_query("UPDATE sections_menu SET link = '{$newlink}' WHERE link = '{$oldlink}'");
					$sql = mysql_query("UPDATE menu SET link = '{$newlink}' WHERE link = '{$oldlink}'");
				}
				
				$query = mysql_query("SELECT * FROM sections_pages WHERE id = {$id}");
			}
			$r = mysql_fetch_assoc($query);
			
			go(HOST . "admin/plugin/sections/sectionpage/{$section_id}/" . $r['id'] . "/Sparat!");
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT3 == "respage")
	{
		if(isset($_POST['pageid']))
		{
			$id = safeText($_POST['pageid']);
			$section_id = safeText($_POST['sectionid']);					
			$title = safeText($_POST['title']);
			$slug = safeText($_POST['slug']);
			$type = "html";
			$html = safeText($_POST['htmltext'], true);
			$link = safeText($_POST['iframelink']);
			$startx = (int)safeText($_POST['startx']);
			$starty = (int)safeText($_POST['starty']);
			
			if(!is_admin())
			{
				if(!in_array($section_id, $_SESSION['sections_rights']))
				{
					adminerror(403);
				}
			}
					
			if($id == "" || $section_id == "" || $title == "" || $slug == "" || $type == "" || ($type == "iframe" && $link == ""))
			{
				adminerror("Något av de obligatoriska fälten är tomt!");
			}
			
			if($id == "Ny resultatsida")
			{
				$query = mysql_query("SELECT * FROM sections_pages WHERE slug = '{$slug}' AND section_id = {$section_id}");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("En sida med den länken finns redan.");
				}
				else
				{
					$sql = mysql_query("INSERT INTO sections_pages (slug,title,text,link,type,startx,starty,extrawide,section_id) VALUES ('{$slug}', '{$title}', '{$html}', '{$link}', '{$type}', {$startx}, {$starty}, 1, {$section_id})");
				}
				$query = mysql_query("SELECT * FROM sections_pages WHERE slug = '{$slug}' AND section_id = {$section_id} AND title = '{$title}' AND type = '{$type}'");
			}
			else
			{
				$query = mysql_query("SELECT * FROM sections_pages WHERE id = {$id}");
				$p = mysql_fetch_assoc($query);
				$sql = mysql_query("SELECT slug FROM sections WHERE id = {$section_id}");
				$s = mysql_fetch_assoc($sql);
				$oldlink = HOST . $s['slug'] . "/" . $p['slug'];
				$newlink = HOST . $s['slug'] . "/" . $slug;
				
				$query = mysql_query("SELECT * FROM sections_pages WHERE slug = '{$slug}' AND section_id = {$section_id} AND id != {$id}");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("En sida med den länken finns redan.");
				}
				else
				{
					$sql = mysql_query("UPDATE sections_pages SET slug = '{$slug}', title = '{$title}', text = '{$html}', link = '{$link}', type = '{$type}', startx = {$startx}, starty = {$starty} WHERE id = {$id}")or exit(mysql_error());
				}
				
				if(!sameValue($oldlink, $newlink))
				{
					$sql = mysql_query("UPDATE sections_menu SET link = '{$newlink}' WHERE link = '{$oldlink}'");
					$sql = mysql_query("UPDATE menu SET link = '{$newlink}' WHERE link = '{$oldlink}'");
				}
				
				$query = mysql_query("SELECT * FROM sections_pages WHERE id = {$id}");
			}
			$r = mysql_fetch_assoc($query);
			
			go(HOST . "admin/plugin/sections/sectionrespage/{$section_id}/" . $r['id'] . "/Sparat!");
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT3 == "news")
	{
		rightsToSee(1,2);
		if(isset($_POST['newsid']))
		{
			$id = safeText($_POST['newsid']);
			$section_id = safeText($_POST['sectionid']);						
			$title = safeText($_POST['title']);
			$slug = safeText($_POST['slug']);
			$html = safeText($_POST['htmltext'], true);
			$date = date("Ymd");
			$time = date("His");
			
			if(!is_admin())
			{
				if(!in_array($section_id, $_SESSION['sections_rights']))
				{
					adminerror(403);
				}
			}
			
			if($id == "" || $section_id == "" || $title == "" || $slug == "")
			{
				adminerror("Något av de obligatoriska fälten är tomt!");
			}
			
			if($id == "Ny nyhet")
			{
				$query = mysql_query("SELECT * FROM sections_news WHERE slug = '{$slug}' AND section_id = {$section_id}");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("En nyhet med den länken finns redan.");
				}
				else
				{
					$sql = mysql_query("INSERT INTO sections_news (slug,title,text,date,time,section_id) VALUES ('{$slug}', '{$title}', '{$html}', '{$date}', '{$time}', {$section_id})");
				}
				$query = mysql_query("SELECT * FROM sections_news WHERE slug = '{$slug}' AND section_id = {$section_id} AND title = '{$title}'");
			}
			else
			{			
				$query = mysql_query("SELECT * FROM sections_news WHERE id = {$id}");
				$p = mysql_fetch_assoc($query);
				$sql = mysql_query("SELECT slug FROM sections WHERE id = {$section_id}");
				$s = mysql_fetch_assoc($sql);
				$oldlink = HOST . $s['slug'] . "/" . $p['slug'];
				$newlink = HOST . $s['slug'] . "/" . $slug;
					
				$query = mysql_query("SELECT * FROM sections_news WHERE slug = '{$slug}' AND section_id = {$section_id} AND id != {$id}");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("En nyhet med den länken finns redan.");
				}
				else
				{
					$sql = mysql_query("UPDATE sections_news SET slug = '{$slug}', title = '{$title}', text = '{$html}' WHERE id = {$id}");
				}
				
				
				if(!sameValue($oldlink, $newlink))
				{
					$sql = mysql_query("UPDATE sections_menu SET link = '{$newlink}' WHERE link = '{$oldlink}'");
					$sql = mysql_query("UPDATE menu SET link = '{$newlink}' WHERE link = '{$oldlink}'");
				}
				
				$query = mysql_query("SELECT * FROM sections_news WHERE id = {$id}");
			}
			$r = mysql_fetch_assoc($query);
			
			go(HOST . "admin/plugin/sections/sectionnew/{$section_id}/" . $r['id'] . "/Sparat!");
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT3 == "menu")
	{
		rightsToSee(1,2);
		if(isset($_POST['menuid']))
		{
			$id = safeText($_POST['menuid']);
			$section_id = safeText($_POST['sectionid']);						
			$title = safeText($_POST['title']);
			$link = safeText($_POST['menulink']);
			$target = safeText($_POST['target']);
			$submenu = safeText($_POST['submenu']);
			$active = safeText($_POST['active']);
			
			if(!is_admin())
			{
				if(!in_array($section_id, $_SESSION['sections_rights']))
				{
					adminerror(403);
				}
			}
			
			if($id == "" || $title == "" || $target == "")
			{
				adminerror("Något av de obligatoriska fälten är tomt!");
			}
			
			if($id == "Ny länk")
			{
				$sql = mysql_query("INSERT INTO sections_menu (title,link,target,submenu,active,section_id) VALUES ('{$title}', '{$link}', '{$target}', {$submenu}, {$active}, {$section_id})");
				$query = mysql_query("SELECT * FROM sections_menu WHERE title = '{$title}' AND link = '{$link}' AND parent = 0 AND section_id = {$section_id}");
			}
			else
			{
				$query = mysql_query("SELECT * FROM sections_menu WHERE id = {$id}");
				$p = mysql_fetch_assoc($query);
				$sql = mysql_query("UPDATE sections_menu SET title = '{$title}', link = '{$link}', target = '{$target}', submenu = {$submenu}, active = {$active} WHERE id = {$id}");
				
				$query = mysql_query("SELECT * FROM sections_menu WHERE id = {$id}");
			}
			$r = mysql_fetch_assoc($query);
			
			go(HOST . "admin/plugin/sections/menu/" . $section_id . "/" . $r['id'] . "/Sparat!");
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT3 == "submenu")
	{
		rightsToSee(1,2);
		$title = safeText($_POST['subtitle']);
		$section_id = safeText($_POST['sectionidsub']);	
		$link = safeText($_POST['submenulink']);
		$parent = (int)safeText($_POST['parent']);
		$target = safeText($_POST['submenutarget']);
			
		if(!is_admin())
		{
			if(!in_array($section_id, $_SESSION['sections_rights']))
			{
				adminerror(403);
			}
		}
		
		if($parent == 0 || $title == "" || $link == "")
		{
			adminerror("Något av de obligatoriska fälten är tomt!");
		}
		$sql = mysql_query("INSERT INTO sections_menu (title,link,parent,target,section_id) VALUES ('{$title}', '{$link}', {$parent}, '{$target}', {$section_id})");
		$query = mysql_query("SELECT * FROM sections_menu WHERE title = '{$title}' AND link = '{$link}' AND parent = {$parent} AND target = '{$target}' AND section_id = {$section_id}");
		$r = mysql_fetch_assoc($query);
		
		go(HOST . "admin/plugin/sections/menu/" .$section_id . "/" . $parent . "/Uppdaterat!");
	}
	elseif(OPT3 == "section")
	{
		rightsToSee(1);
		$id = safeText($_POST['pageid']);
		$name = safeText($_POST['name']);
		$slug = safeText($_POST['slug']);
		
		if($id == "" || $name == "" || $slug == "")
		{
			adminerror("Något av de obligatoriska fälten är tomt!");
		}
		
			
		if($id == "Ny sektion")
		{
			$query = mysql_query("SELECT * FROM sections WHERE slug = '{$slug}'");
			if(mysql_num_rows($query) != 0)
			{
				adminerror("En sektion med den länken finns redan.");
			}
			else
			{
				$sql = mysql_query("INSERT INTO sections (name,slug) VALUES ('{$name}', '{$slug}')");
				$query = mysql_query("SELECT * FROM sections WHERE slug = '{$slug}' AND name = '{$name}'");
				$r = mysql_fetch_assoc($query);
				$sid = $r['id'];
				$date = date("Ymd");
				$time = date("His");
				$nyhet = "<p>Detta &auml;r ett exempel p&aring; en nyhet.</p><p> N&auml;r du har skapat din sektion b&ouml;r du ta bort denna nyhet i administrationspanelen och skapa en egen nyhet ist&auml;llet.</p><p>&nbsp;</p><p> Lycka till med din nya sektion!</p>";
				$page = "<p><strong><em>Grattis!</em></strong></p><p>Du har precis skapat en ny sektion. </p><p>Detta &auml;r din startsida. </p><p>F&ouml;r att &auml;ndra denna texten g&ouml;r du f&ouml;ljande:</p><ol><li>Logga in i administrationspanelen</li><li>Klicka p&aring; Sidor i Publicera-menyn (Har du tillg&aring;ng till flera sektionen m&aring;ste du v&auml;lja {$name} i dropdown-menyn)</li><li>Klicka p&aring; raden med rubriken Startsidan</li><li>Nu &auml;r du inne p&aring; sidan d&auml;r du kan &auml;ndra denna text och dess rubrik till vad du vill.</li><li>Klicka p&aring; spara n&auml;r du &auml;r klar!</li></ol><p><strong>Lycka till med din nya sektion!</strong></p>";
				
				$sql = mysql_query("INSERT INTO sections_menu (title,link,target,submenu,active,section_id) VALUES ('Nyheter', '". HOST . $slug . "/news', '_self', 0, 1, {$sid})");
				$sql = mysql_query("INSERT INTO sections_news (slug,title,text,date,time,section_id) VALUES ('exempelnyhet', 'Exempelnyhet', '{$nyhet}', '{$date}', '{$time}', {$sid})");
				$sql = mysql_query("INSERT INTO sections_pages (slug,title,text,type,section_id) VALUES ('start', 'Startsidan', '{$page}', 'html', {$sid})");
			}
			$query = mysql_query("SELECT * FROM sections WHERE slug = '{$slug}' AND name = '{$name}'");
		}
		else
		{			
			$query = mysql_query("SELECT * FROM sections WHERE id = {$id}");
			$p = mysql_fetch_assoc($query);
			$oldlink = HOST . $p['slug'];
			$newlink = HOST . $slug;
					
			$query = mysql_query("SELECT * FROM sections WHERE slug = '{$slug}' AND id != {$id}");
			if(mysql_num_rows($query) != 0)
			{
				adminerror("En sektion med den länken finns redan.");
			}
			else
			{
				$sql = mysql_query("UPDATE sections SET slug = '{$slug}', name = '{$name}' WHERE id = {$id}");
			}
			
			
			if(!sameValue($oldlink, $newlink))
			{
				$sql = mysql_query("UPDATE sections_menu SET link = '{$newlink}' WHERE link = '{$oldlink}'");
				$sql = mysql_query("UPDATE menu SET link = '{$newlink}' WHERE link = '{$oldlink}'");
			}
				
			$query = mysql_query("SELECT * FROM sections WHERE id = {$id}");
		}
		$r = mysql_fetch_assoc($query);
		
		go(HOST . "admin/plugin/sections/section/" . $r['id'] . "/Uppdaterat!");
	}
	elseif(OPT3 == "settings")
	{
		rightsToSee(1);
		if(isset($_POST['changedfields']))
		{
			$fieldstring = substr($_POST['changedfields'],0,(strlen($_POST['changedfields'])-1));
			if(trim($fieldstring) != "")
			{
				$fields = explode(",", $fieldstring);
				$savedfields = array();
				$numFields = 0;
				foreach($fields as $field)
				{
					if(!in_array($field, $savedfields))
					{
						$value = safeText($_POST[$field]);
						array_push($savedfields, $field);
						set_setting($field, $value);
						$numFields++;
					}
				}
				go(HOST . "admin/plugin/sections/settings/". $numFields . " fält har sparats!");
			}
			else
			{
				go(HOST . "admin/plugin/sections/settings/Inga ändringar har gjorts!");
			}
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT3 == "rights")
	{
		rightsToSee(1);
		$section_id = safeText($_POST['sectionid']);
		$user = (int)safeText($_POST['user']);
		if($user == 0)
		{
			go(HOST . "admin/plugin/sections/userrights/" . $section_id . "/1/Välj en användare!");
		}
		
		$sql = mysql_query("SELECT * FROM sections_userrights WHERE userid = {$user} AND section_id = {$section_id}");
		if(mysql_num_rows($sql) == 0)
		{
			$sql = mysql_query("INSERT INTO sections_userrights (userid,section_id) VALUES ({$user}, {$section_id})");
			go(HOST . "admin/plugin/sections/userrights/" . $section_id . "/1/Sparat!");
		}
		else
		{
			go(HOST . "admin/plugin/sections/userrights/" . $section_id . "/1/Användaren har redan tillgång till sektionen!");
		}
	}
}
else
{
	adminerror(404);
}
?>