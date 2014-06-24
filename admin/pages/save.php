<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

if(defined("OPT1"))
{
	if(OPT1 == "user")
	{
		rightsToSee(1);
		if(isset($_POST['userid']))
		{
			$id = safeText($_POST['userid']);					
			$name = safeText($_POST['name']);
			$email = safeText($_POST['email']);
			$username = safeText($_POST['username']);
			$userclass = (int)safeText($_POST['userclass']);
			
			if($id == "" || $name == "" || $email == "" || $username == "" || $userclass == 0)
			{
				adminerror("Något av de obligatoriska fälten är tomt!");
			}
			
			if($id == "Nytt konto")
			{
				if(trim($_POST['user_password']) == "" || trim($_POST['user_password']) != trim($_POST['user_password_again']))
				{
					adminerror("Lösenordet är oglitligt eller så stämmer inte lösenorde överäns med varandra.");
				}
				$query = mysql_query("SELECT * FROM userbase WHERE username = '{$username}' OR email = '{$email}'");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("Användaren finns redan i databasen");
				}
				else
				{
					$md5pw = md5($_POST['user_password']);
					$sql = mysql_query("INSERT INTO userbase (username,password,email,userclass,name) VALUES ('{$username}', '{$md5pw}', '{$email}', $userclass, '{$name}')");
					
					// Mailar den nya användaren med uppgifter.
					
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

					$to = $email;
					$from = $server_mail;
					$subject = "Nytt konto";
					$srvname = $page_title . " - Server";
					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/plain; charset=utf-8\r\n";
					$headers .= "Content-Transfer-Encoding: 8bit\r\n";
					$headers .= "From: $srvname <{$from}> \r\n"; 
					$headers .= "Reply-To: Support <{$support_mail}> \r\n"; 
					$text = "Hej {$name}!

Det har skapats ett användarkonto åt dig på sidan " . HOST . ". 

Här kommer dina uppgifter som du använder för att logga in på administrationspanelen.

ANVÄNDARNAMN: {$username}

NYTT LÖSENORD: " .$_POST['user_password']."

När du har loggat in första gången bör du byta ut detta lösenordet.
För att byta lösenord väljer du \"Mitt konto\" i menyn \"Inställningar\".
Klicka på knappen \"Byt lösenord\". Fyll i ditt nya lösenord i båda fälten och tryck sedan på spara.

Tänk på att inte lämna ut dessa uppgifter till någon annan.

Skulle problem uppstå.
Kontakta webbmaster på {$support_mail}

";
				
					mail($to, $subject, $text , $headers);
				}
				$query = mysql_query("SELECT * FROM userbase WHERE name = '{$name}' AND email = '{$email}' AND username = '{$username}'");
			}
			else
			{
				if(empty($_POST['user_password']))
				{
					$sql = mysql_query("UPDATE userbase SET name = '{$name}', email = '{$email}', userclass = {$userclass}, username = '{$username}' WHERE id = {$id}");					
				}
				else
				{
					if(trim($_POST['user_password']) != trim($_POST['user_password_again']))
					{
						adminerror("Lösenordet är oglitligt eller så stämmer inte lösenorde överäns med varandra.");
					}
					else
					{
						$md5pw = md5($_POST['user_password']);
						$sql = mysql_query("UPDATE userbase SET name = '{$name}', email = '{$email}', userclass = {$userclass}, username = '{$username}', password = '{$md5pw}' WHERE id = {$id}");
					}
				}
				$query = mysql_query("SELECT * FROM userbase WHERE id = {$id}");
			}
			$r = mysql_fetch_assoc($query);
			
			go(HOST . "admin/user/" . $r['id'] . "/Sparat!");
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT1 == "page")
	{
		rightsToSee(1);
		if(isset($_POST['pageid']))
		{
			$id = safeText($_POST['pageid']);					
			$title = safeText($_POST['title']);
			$slug = safeText($_POST['slug']);
			$type = safeText($_POST['type']);
			$html = safeText($_POST['htmltext'], true);
			$link = safeText($_POST['iframelink']);
			$startx = (int)safeText($_POST['startx']);
			$starty = (int)safeText($_POST['starty']);
			$formid = (int)safeText($_POST['formid']);
			//echo $html;
			
			if($id == "" || $title == "" || $slug == "" || $type == "" || ($type == "iframe" && $link == ""))
			{
				adminerror("Något av de obligatoriska fälten är tomt!");
			}
			
			if($id == "Ny sida")
			{
				$query = mysql_query("SELECT * FROM pages WHERE slug = '{$slug}'");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("En sida med den länken finns redan.");
				}
				else
				{
					$sql = mysql_query("INSERT INTO pages (slug,title,text,link,type,startx,starty,form) VALUES ('{$slug}', '{$title}', '{$html}', '{$link}', '{$type}', {$startx}, {$starty}, {$formid})");
				}
				$query = mysql_query("SELECT * FROM pages WHERE slug = '{$slug}' AND title = '{$title}' AND type = '{$type}'");
			}
			else
			{
				$query = mysql_query("SELECT * FROM pages WHERE id = {$id}");
				$p = mysql_fetch_assoc($query);
				$oldlink = HOST . $p['slug'];
				$newlink = HOST . $slug;
				
				$query = mysql_query("SELECT * FROM pages WHERE slug = '{$slug}' AND id != {$id}");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("En sida med den länken finns redan.");
				}
				else
				{
					$sql = mysql_query("UPDATE pages SET slug = '{$slug}', title = '{$title}', text = '{$html}', link = '{$link}', type = '{$type}', startx = {$startx}, starty = {$starty}, form = {$formid} WHERE id = {$id}")or exit(mysql_error());
				}
				
				if(!sameValue($oldlink, $newlink))
				{
					$sql = mysql_query("UPDATE menu SET link = '{$newlink}' WHERE link = '{$oldlink}'");
				}
				
				$query = mysql_query("SELECT * FROM pages WHERE id = {$id}");
			}
			$r = mysql_fetch_assoc($query);
			
			go(HOST . "admin/page/" . $r['id'] . "/Sparat!");
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT1 == "news")
	{
		rightsToSee(1,2);
		if(isset($_POST['newsid']))
		{
			$id = safeText($_POST['newsid']);					
			$title = safeText($_POST['title']);
			$slug = safeText($_POST['slug']);
			$html = safeText($_POST['htmltext'], true);
			$date = date("Ymd");
			$time = date("His");
			
			if($id == "" || $title == "" || $slug == "")
			{
				adminerror("Något av de obligatoriska fälten är tomt!");
			}
			
			if($id == "Ny nyhet")
			{
				$query = mysql_query("SELECT * FROM news WHERE slug = '{$slug}'");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("En sida med den länken finns redan.");
				}
				else
				{
					$sql = mysql_query("INSERT INTO news (slug,title,text,date,time) VALUES ('{$slug}', '{$title}', '{$html}', '{$date}', '{$time}')");
				}
				$query = mysql_query("SELECT * FROM news WHERE slug = '{$slug}' AND title = '{$title}'");
			}
			else
			{				
				$query = mysql_query("SELECT * FROM news WHERE slug = '{$slug}' AND id != {$id}");
				if(mysql_num_rows($query) != 0)
				{
					adminerror("En sida med den länken finns redan.");
				}
				else
				{
					$sql = mysql_query("UPDATE news SET slug = '{$slug}', title = '{$title}', text = '{$html}' WHERE id = {$id}");
				}
				$query = mysql_query("SELECT * FROM news WHERE id = {$id}");
			}
			$r = mysql_fetch_assoc($query);
			
			go(HOST . "admin/new/" . $r['id'] . "/Sparat!");
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT1 == "menu")
	{
		rightsToSee(1);
		if(isset($_POST['menuid']))
		{
			$id = safeText($_POST['menuid']);					
			$title = safeText($_POST['title']);
			$link = safeText($_POST['menulink']);
			$target = safeText($_POST['target']);
			$submenu = safeText($_POST['submenu']);
			$active = safeText($_POST['active']);
			
			if($id == "" || $title == "" || $target == "")
			{
				adminerror("Något av de obligatoriska fälten är tomt!");
			}
			
			if($id == "Ny länk")
			{
				$sql = mysql_query("INSERT INTO menu (title,link,target,submenu,active) VALUES ('{$title}', '{$link}', '{$target}', {$submenu}, {$active})");
				$query = mysql_query("SELECT * FROM menu WHERE title = '{$title}' AND link = '{$link}' AND parent = 0");
			}
			else
			{
				$query = mysql_query("SELECT * FROM menu WHERE id = {$id}");
				$p = mysql_fetch_assoc($query);
				$sql = mysql_query("UPDATE menu SET title = '{$title}', link = '{$link}', target = '{$target}', submenu = {$submenu}, active = {$active} WHERE id = {$id}");
				
				$query = mysql_query("SELECT * FROM menu WHERE id = {$id}");
			}
			$r = mysql_fetch_assoc($query);
			
			go(HOST . "admin/menu/" . $r['id'] . "/Sparat!");
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT1 == "submenu")
	{
		rightsToSee(1);
		$title = safeText($_POST['subtitle']);
		$link = safeText($_POST['submenulink']);
		$parent = (int)safeText($_POST['parent']);
		$target = safeText($_POST['submenutarget']);
		
		if($parent == 0 || $title == "" || $link == "")
		{
			adminerror("Något av de obligatoriska fälten är tomt!");
		}
		$sql = mysql_query("INSERT INTO menu (title,link,parent,target) VALUES ('{$title}', '{$link}', {$parent}, '{$target}')");
		$query = mysql_query("SELECT * FROM menu WHERE title = '{$title}' AND link = '{$link}' AND parent = {$parent} AND target = '{$target}'");
		$r = mysql_fetch_assoc($query);
		
		go(HOST . "admin/menu/" . $parent . "/Uppdaterat!");
	}
	elseif(OPT1 == "my-settings")
	{
		if(isset($_SESSION['userid']))
		{
			$id = safeText($_SESSION['userid']);
			$email = safeText($_POST['email']);
			
			if($id == "" || $email == "")
			{
				adminerror("Något av de obligatoriska fälten är tomt!");
			}
		
			if(empty($_POST['user_password']))
			{
				$sql = mysql_query("UPDATE userbase SET email = '{$email}' WHERE id = {$id}");					
			}
			else
			{
				if(trim($_POST['user_password']) != trim($_POST['user_password_again']))
				{
					adminerror("Lösenordet är oglitligt eller så stämmer inte lösenorde överäns med varandra.");
				}
				else
				{
					$md5pw = md5($_POST['user_password']);
					$sql = mysql_query("UPDATE userbase SET email = '{$email}', password = '{$md5pw}' WHERE id = {$id}");
				}
			}			
			go(HOST . "admin/my-settings/Sparat!");
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT1 == "settings")
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
				go(HOST . "admin/general-settings/". $numFields . " fält har sparats!");
			}
			else
			{
				go(HOST . "admin/general-settings/Inga ändringar har gjorts!");
			}
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT1 == "theme")
	{
		rightsToSee(1);
		if(defined("OPT2") && defined("OPT3"))
		{
			$oldtheme = get_setting("theme");
			$slug = safeText(OPT2);
			$name = safeText(OPT3);
			$oTheme = readTheme($oldtheme);
			$nTheme = readTheme($slug);
			if($oTheme['plugin'])
			{
				loadPlugin($oldtheme, "inactivate", false, "theme");;
			}
			if($nTheme['plugin'])
			{
				loadPlugin($slug, "activate", true, "theme");
			}
			set_setting("theme", $slug);
			set_setting("themename", $name);
			go(HOST . "admin/themes/Temat har aktiverats!");
		}
		else
		{
			adminerror(404);
		}
	}
	elseif(OPT1 == "form")
	{
		rightsToSee(1);
		$errors = array();
		if(defined("OPT2") && isset($_POST['title']) && isset($_POST['object-string'])) {
			$formId = safeText(OPT2);
			$formNewTitle = settingSafeText($_POST['title']);
			$settingsString = "title:".$formNewTitle;
					$objectsToRemove = array();
			if(isset($_POST['email']) && !empty($_POST['email'])) {
				$email = settingSafeText($_POST['email']);
				$settingsString .= "|emailto:" . $email;
			}
			if(isset($_POST['submitmessage'])) {
				$submitmessage = settingSafeText($_POST['submitmessage']);
				$settingsString .= "|message:" . $submitmessage;
			}
			
			if($formId == "Nytt formulär") {
				$sqlIns = mysql_query("INSERT INTO `forms` (`type`, `settings`) VALUES ('form','" . $settingsString . "')");
				$insRes = mysql_query("SELECT `id` FROM `forms` WHERE `type` = 'form' AND `settings` = '". $settingsString . "' ORDER BY `id` DESC LIMIT 1");
				if(mysql_num_rows($insRes) == 1) {
					$insRow = mysql_fetch_assoc($insRes);
				}
				$formId = $insRow['id'];
			}
			else {
				$sqlUpd = mysql_query("UPDATE `forms` SET `settings` = '" . $settingsString . "' WHERE `id` = ". $formId . "");
				if($sql = mysql_query("SELECT `id` FROM `forms_objects` WHERE `formid` = '" .$formId. "'")) {
					$objectsToRemoveLength = mysql_num_rows($sql);
					while($objR = mysql_fetch_assoc($sql)) {
						array_push($objectsToRemove, $objR['id']);
					}
				}
			}
			if(!empty($_POST['object-string'])) {
				$objects = explode("|", $_POST['object-string']);
				$objectsIds = array();
				foreach($objects as $obj) {
					$content = safeText($_POST[$obj . "-content"]);
					$settings = safeText($_POST[$obj . "-settings"]);
					$metaObj = explode("|", $_POST[$obj . "-meta"]);
					$meta = array();
					foreach($metaObj as $sObj) {
						$sObj = explode(":", $sObj, 2);
						$meta[$sObj[0]] = safeText($sObj[1]);
					}
					if($meta['id'] == "new") {
						$sql = mysql_query("INSERT INTO `forms_objects` (`formid`, `title`, `content`, `settings`, `sort`, `type`) VALUES (" .$formId. ", '" .$meta['title']. "', '" .$content. "', '" .$settings. "', '" .$meta['sort']. "', '" .$meta['type']. "')");
					}
					else {
						if($sql = mysql_query("UPDATE `forms_objects` SET `settings` = '" .$settings. "', `sort` = '" .$meta['sort']. "', `type` = '" .$meta['type']. "', `title` = '" .$meta['title']. "', `content` = '" .$content. "' WHERE `id` = " .$meta['id']. "")) {
						}
						else {
							array_push($errors, "Error code: 601");
						}
						array_push($objectsIds, $meta['id']);
						for($i = 0; $i < $objectsToRemoveLength; $i++) {
							if(isset($objectsToRemove[$i]) && $objectsToRemove[$i] == $meta['id']) {
								unset($objectsToRemove[$i]);
								break;
							}
						}
					}
				}
			}
			
			// Clean up database from old objects that have been removed
			foreach($objectsToRemove as $key) {
				$sqlResult = mysql_query("DELETE FROM `forms_objects` WHERE `id` = {$key}");
			}
		}
		else {
			array_push($errors, "Error code: 602");
		}
		if(count($errors) == 0) {
			if(isset($_POST['return-url']) && !empty($_POST['return-url'])) {
				go(safeText($_POST['return-url']));
			}
			else {
				go(HOST."admin/form/". $formId);
			}
		}
		else {
			echo "<h1>ERROR!</h1>";
			foreach($errors as $error) {
				echo "<p>" . $error . "</p>";
			}
		}	
	}
}
else
{
	adminerror(404);
}
?>