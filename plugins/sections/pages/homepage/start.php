<?PHP
global $pagevars;


if(SECTION_ID == get_setting("default_section") && get_setting("use_sectionstart_default") == "false")
{
	$page = THEME_FOLDER . "index.php";
}
else
{
	if(file_exists(THEME_FOLDER . "section_index.php"))
	{
		$page = THEME_FOLDER . "section_index.php";
	}
	else
	{
		$sql = mysql_query("SELECT * FROM sections_pages WHERE slug = 'start' AND section_id = ". SECTION_ID ."");
		if(mysql_num_rows($sql) == 1)
		{
			$pagevars = mysql_fetch_assoc($sql);
			$page = "plugins/sections/pages/homepage/sectionsdbpage.php";
		}
		else
		{
			error(404);
		}
	}
}
include($page);
?>