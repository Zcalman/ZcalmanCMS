<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1,2);
$activetheme = get_setting("theme");
$activethemename = get_setting("themename");
$themeslist = new Table(7, "Bild|Namn|Skapare|Version|Actions|Actions|Actions", "250|250|250|250|250|250|250", false);
$dir = "../themes";
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
				$themevars = readTheme($file);
				if($themevars !== false)
				{
					$imagefile = $host . "themes/" . $file . "/{$themevars['thumbimage']}";
					$imagefileshort = "../themes/" . $file . "/{$themevars['thumbimage']}";
					if(trim($themevars['thumbimage']) == "" || !file_exists($imagefileshort))
					{
						$imagefile = $host . "admin/images/icons/noimg.gif";
					}
					$marked = false;
					if($activetheme == $file)
					{
						$marked = true;
					}
					$themeslist->addRow($file, "<img src=\"" . $imagefile . "\" />|<h3>Namn:</h3> {$themevars['title']}|<h3>Skapare:</h3> {$themevars['creator']}|<h3>Version:</h3> {$themevars['version']}|<a onclick='javascript: previewTheme()'>Förhandsgranska</a>|<a onclick='javascript: securityAskChangeTheme(\"{$themevars['title']}\",\"{$file}\")'>Aktivera</a>|<a onclick='javascript: securityAskDeleteTheme(\"{$themevars['title']}\",\"{$file}\")'>Ta bort</a>", $marked);
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
Temahantering
</div>
<div class="description">
Här väljer du vilket tema som ska användas just nu.<br />
<br />
Aktivt tema: <strong><?PHP echo $activethemename; ?></strong>
</div>
<div id="themes">
<?PHP
$themeslist->printTable();
?>
</div>