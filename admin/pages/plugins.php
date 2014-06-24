<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
$activePluginsList = new Table(2, "Tillägg|Beskrivning", "232|560", "");
$sql = mysql_query("SELECT * FROM plugins WHERE `active` = 1 AND `installed` = 1");
while($r = mysql_fetch_assoc($sql))
{
	$pluginmeta = loadPluginInfo($r['slug']);
	$webpage = "";
	if($pluginmeta['url'] != "")
	{
		$webpage = "| <a href='{$pluginmeta['url']}' target='_new' class='pluginlistaction'>Hemsida</a>";
	}
	$activePluginsList->addRow($r['id'], "<strong>{$pluginmeta['name']}</strong><br /><a href='javascript:inactivatePlugin(\"{$pluginmeta['slug']}\")' class='pluginlistaction'>Inaktivera</a>|". cutText($pluginmeta['description'], 70, false) ."<br /><span class='smalltext'>Version {$pluginmeta['version']} | Skapad av {$pluginmeta['creator']} {$webpage}</span>");
}

$inactivePluginsList = new Table(2, "Tillägg|Beskrivning", "232|560", "");
$sql = mysql_query("SELECT * FROM plugins WHERE `active` = 0 AND `installed` = 1");
while($r = mysql_fetch_assoc($sql))
{
	$pluginmeta = loadPluginInfo($r['slug']);
	$webpage = "";
	if($pluginmeta['url'] != "")
	{
		$webpage = "| <a href='{$pluginmeta['url']}' target='_new' class='pluginlistaction'>Hemsida</a>";
	}
	$inactivePluginsList->addRow($r['id'], "<strong>{$pluginmeta['name']}</strong><br /><a href='javascript:activatePlugin(\"{$pluginmeta['slug']}\")' class='pluginlistaction'>Aktivera</a> <a href='javascript:uninstallPlugin(\"{$pluginmeta['name']}\",\"{$pluginmeta['slug']}\")' class='pluginlistaction'>Avinstallera</a>|". cutText($pluginmeta['description'], 70, false) ."<br /><span class='smalltext'>Version {$pluginmeta['version']} | Skapad av {$pluginmeta['creator']} {$webpage}</span>");
}

$uninstalledPluginsList = new Table(2, "Tillägg|Beskrivning", "232|560", "");
$dir = "../plugins";
// Open a known directory, and proceed to read its contents
if (is_dir($dir)) {
    if ($dh = opendir($dir))
	{
		$i = 0;
        while (($file = readdir($dh)) !== false)
		{
			if (($file == '.')||($file == '..'))
            {
            }
			elseif($file == 'Thumbs.db') 
			{
			}
			elseif($file == 'thumbs') 
			{
			}
			elseif(is_dir($dir . "/" . $file))
			{
				$pluginmeta = loadPluginInfo($file);
				if($pluginmeta !== false)
				{
					$sql = mysql_query("SELECT id FROM plugins WHERE slug = '{$pluginmeta['slug']}' LIMIT 1");
					if(mysql_num_rows($sql) == 0)
					{
						$webpage = "";
						if($pluginmeta['url'] != "")
						{
							$webpage = "| <a href='{$pluginmeta['url']}' target='_new' class='pluginlistaction'>Hemsida</a>";
						}
						$uninstalledPluginsList->addRow($r['id'], "<strong>{$pluginmeta['name']}</strong><br /><a href='javascript:installPlugin(\"{$pluginmeta['name']}\",\"{$pluginmeta['slug']}\")' class='pluginlistaction'>Installera</a> <a href='javascript:deletePlugin(\"{$pluginmeta['name']}\",\"{$pluginmeta['slug']}\")' class='pluginlistaction'>Radera</a>|". cutText($pluginmeta['description'], 70, false) ."<br /><span class='smalltext'>Version {$pluginmeta['version']} | Skapad av {$pluginmeta['creator']} {$webpage}</span>");
					}
				}
			}
        }
        closedir($dh);
    }
}
?>
<?PHP if(defined("OPT1"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT1) . "</div>";
}
?>
<div class="pagetitle">
Hantering av tillägg
</div>
<div class="description">
Här väljer du vilka tillägg som ska vara aktiva just nu.<br /><br />
</div>
<h2>Aktiva tillägg</h2>
<div class="pluginlist">
<?PHP
$activePluginsList->printTable();
?>
</div>
<div class="space" style="height:20px;"></div>
<h2>Inaktiva tillägg</h2>
<div class="pluginlist">
<?PHP
$inactivePluginsList->printTable();
?>
</div>
<div class="space" style="height:20px;"></div>
<h2>Ej installerade tillägg</h2>
<div class="pluginlist">
<?PHP
$uninstalledPluginsList->printTable();
?>
</div>