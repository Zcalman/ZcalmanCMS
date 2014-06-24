<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
if(defined("OPT1") && defined("OPT2"))
{
	if(OPT1 == "activate")
	{
		$slug = safeText(OPT2);
		if(loadPlugin($slug, "activate") == false)
		{
			go($host . "admin/plugins/Kunde inte aktivera tillägget!");
		}
		$sql = mysql_query("UPDATE plugins SET active = 1 WHERE `slug` = '{$slug}' AND `active` = 0 AND `installed` = 1 LIMIT 1") or go($host . "admin/plugins/Kunde inte aktivera tillägget!");
		if(mysql_affected_rows() == 0)
		{
			go($host . "admin/plugins/Kunde inte aktivera tillägget!");
		}
		go($host . "admin/plugins/Tillägget har aktiverats!");
	}
	elseif(OPT1 == "inactivate")
	{
		$slug = safeText(OPT2);
		if(loadPlugin($slug, "inactivate", false) == false)
		{
			go($host . "admin/plugins/Kunde inte avaktivera tillägget!");
		}
		$sql = mysql_query("UPDATE plugins SET active = 0 WHERE `slug` = '{$slug}' AND `active` = 1 AND `installed` = 1 LIMIT 1") or go($host . "admin/plugins/Kunde inte avaktivera tillägget!");
		if(mysql_affected_rows() == 0)
		{
			go($host . "admin/plugins/Kunde inte avaktivera tillägget!");
		}
		go($host . "admin/plugins/Tillägget har avaktiverats!");
	}
	elseif(OPT1 == "uninstall")
	{
		$slug = safeText(OPT2);
		if(loadPlugin($slug, "uninstall") == false)
		{
			go($host . "admin/plugins/Kunde inte avinstallera tillägget!");
		}
		$sql = mysql_query("DELETE FROM plugins WHERE `slug` = '{$slug}' AND `active` = 0 AND `installed` = 1 LIMIT 1") or go($host . "admin/plugins/Kunde inte avinstallera tillägget!");
		if(mysql_affected_rows() == 0)
		{
			go($host . "admin/plugins/Kunde inte avinstallera tillägget!");
		}
		go($host . "admin/plugins/Tillägget har avinstallerats!");
	}
	elseif(OPT1 == "install")
	{
		$slug = safeText(OPT2);
		$pluginmeta = loadPluginInfo($slug);
		if($pluginmeta !== false)
		{
			$sql1 = mysql_query("SELECT * FROM plugins WHERE `slug` = '{$slug}'");
			if(mysql_num_rows($sql1) > 0)
			{
				// Radera ev. tidigare rad i databas om det skulle finnas. Kontroll i loadPluginInfo() som motverkar plugins med samma slug och gör det därmed omöjligt att pluginet används.
				$sql2 = mysql_query("DELETE FROM plugins WHERE `slug` = '{$slug}'") or go($host . "admin/plugins/Kunde inte installera tillägget!");
			}
			if(loadPlugin($slug, "install") == false)
			{
				go($host . "admin/plugins/Kunde inte installera tillägget!");
			}
			$sql3 = mysql_query("INSERT INTO plugins (slug,installed) VALUES ('{$slug}', 1)") or go($host . "admin/plugins/Kunde inte installera tillägget!");
			go($host . "admin/plugins/Tillägget har installerats!");
		}
		else
		{
			go($host . "admin/plugins/Ett fel i tilläggets uppbyggnad gör det omöjligt att installera!");
		}
	}
	elseif(OPT1 == "delete")
	{
		$slug = safeText(OPT2);
		$dir = "../plugins/{$slug}";
		$sql1 = mysql_query("SELECT * FROM plugins WHERE `slug` = '{$slug}'");
		if(mysql_num_rows($sql1) > 0)
		{
			go($host . "admin/plugins/Du kan inte radera ett tillägg som är installerat.");
		}
		else
		{
			removeDir($dir);
			go($host . "admin/plugins/Tillägget har raderats!");
		}
		
	}
}
?>