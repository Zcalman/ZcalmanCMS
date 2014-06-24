<?PHP
// Awesome code goes here
global $pagevars;
$slug = $pagevars['slug'];
$section = array("id" => SECTION_ID, "name" => SECTION_NAME, "slug" => SECTION_SLUG); 
if(defined("OPT1") && OPT1 != "")
{
	$page = OPT1;
}
else
{
	$page = "start";
}
switch($page)
{
	case "start":
		$pagevars = array("title" => "Startsidan", "file" => "start.php", "slug" => $page, "section" => $section);
		break;
	case "news":
		$pagevars = array("title" => "Nyheter", "file" => "news.php", "slug" => $page, "section" => $section);
		break;
	case "article":
		$pagevars = array("title" => "Nyhetsartikel", "file" => "article.php", "slug" => $page, "section" => $section);
		break;
	case "error":
		$pagevars = array("title" => "Felmeddelande", "file" => "error.php", "slug" => $page, "section" => $section);
		break;
	default:
		$sql = mysql_query("SELECT * FROM sections_pages WHERE slug = '{$page}' AND section_id = {$section['id']}");
		$numres = mysql_num_rows($sql);
		if($numres == 1)
		{
			$pagevars = mysql_fetch_assoc($sql);
			$pagevars['text'] = stripslashes($pagevars['text']);
			$pagevars['file'] = "sectionsdbpage.php";
			$pagevars['section'] = $section;
		}
		else
		{
			sections_code_error(404);
		}
		break;
}
include("plugins/sections/pages/homepage/" . $pagevars['file']);
?>