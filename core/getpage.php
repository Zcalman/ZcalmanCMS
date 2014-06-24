<?php require("core/pages.php"); ?>
<?PHP
$pagevars = NULL;
if (isset($_GET['page']) && $_GET['page'] != "") 
{
	$page = safeText($_GET['page']);
}
else 
{
	$page = "start";
}
$folder = "core/pages/";
if (array_key_exists($page, $pages)) 
{
	if(file_exists($folder.$pages[$page]))
	{
		switch($page)
		{
			case "start":
				$pagevars = array("title" => "Startsidan", "file" => "start.php", "slug" => $page);
				break;
			case "news":
				$pagevars = array("title" => "Nyheter", "file" => "news.php", "slug" => $page);
				break;
			case "error":
				$pagevars = array("title" => "Felmeddelande", "file" => "error.php", "slug" => $page);
				break;
			case "article":
				$pagevars = array("title" => "Nyhetsartikel", "file" => "article.php", "slug" => $page);
				break;
			case "reset_portable_version":
				$pagevars = array("title" => "Reset portable version", "file" => "reset_portable_version.php", "slug" => $page);
				break;
			case "use_normal_version":
				$pagevars = array("title" => "Skickar vidare till vanliga versionen", "file" => "use_normal_version.php", "slug" => $page);
				break;
			case "use_portable_version":
				$pagevars = array("title" => "Skickar vidare till mobilversionen", "file" => "use_portable_version.php", "slug" => $page);
				break;
			case "form":
				$pagevars = array("title" => "Hanterar formulär begäran", "file" => "form.php", "slug" => $page);
				break;
			default:
				break;
		}
		$content = $folder.$pagevars["file"];
	}
	else
	{
		$content = get_errorpage("404");
	}
}
elseif(array_key_exists($page, $pluginpages))
{
	$p = $pluginpages[$page];
	$pagevars = array("title" => $p->title, "file" => $p->pfile, "slug" => $page);
	$content = "plugins/".$p->plugin."/".$p->pfile;
	if($p->type == "theme")
	{
		$content = "themes/".$p->plugin."/".$p->pfile;
	}
	if(!file_exists($content))
	{
		$content = get_errorpage("404");
	}
}
else 
{
	$sql = mysql_query("SELECT * FROM pages WHERE slug = '{$page}'");
	$numres = mysql_num_rows($sql);
	if($numres == 1)
	{
		$pagevars = mysql_fetch_assoc($sql);
		$pagevars['text'] = stripslashes($pagevars['text']);
		$content = $folder . "databasepage.php";
	}
	else
	{
		$content = get_errorpage("404");
	}
}
global $content;
define("PAGE", $page);
?>