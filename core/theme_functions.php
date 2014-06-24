<?PHP
function zc_head()
{
	echo "
	<meta name=\"generator\" content=\"Zimba CMS\" />
    <script type=\"text/javascript\">
		var host = \"" . HOST . "\";
		var page = \"" . PAGE . "\";
		function goto(to)
		{
			top.location.href = to;
		}
	</script>
	";
	zc_action_control('htmlhead');
}
function zc_menu()
{
	echo "<ul id=\"menu\">";
	$sql = mysql_query("SELECT * FROM menu WHERE parent = 0 AND active = 1 ORDER BY `sort` ASC");
	while($r = mysql_fetch_assoc($sql))
    {
        $query = mysql_query("SELECT * FROM menu WHERE parent = {$r['id']} AND active = 1 ORDER BY `sort` ASC");
		if($r['link'] != "#")
		{
        	echo "<li><a href=\"" . $r['link'] . "\" target=\"" . $r['target'] . "\" title=\"" . $r['title'] . "\">" . $r['title'] . "</a>";
		}
		else
		{
			echo "<li><a title=\"" . $r['title'] . "\">" . $r['title'] . "</a>";
		}
		if(mysql_num_rows($query) > 0 && $r['submenu'] == 1)
		{
			echo "<ul>";
			while($s = mysql_fetch_assoc($query))
			{
				echo "<li><a href=\"" . $s['link'] . "\" target=\"" . $s['target'] . "\" title=\"" . $s['title'] . "\">" . $s['title'] . "</a></li>";
			}
			echo "</ul>";
		}
		echo "</li>";
    }
    echo "</ul>";
}
function zc_page_top()
{
	$header = THEME_FOLDER . "/header.php";
	include($header);
	zc_action_control('beforecontent');
}
function zc_page_bottom()
{
	zc_action_control('aftercontent');
	zc_action_control('beforefooter');
	$footer = THEME_FOLDER . "/footer.php";
	include($footer);
}
function zc_title()
{
	echo PAGE_TITLE;
}
function zc_get_title()
{
	return PAGE_TITLE;
}
function zc_desc()
{
	echo PAGE_DESCRIPTION;
}
function zc_get_desc()
{
	return PAGE_DESCRIPTION;
}
function theme_url()
{
	echo THEME_URL;
}
function get_theme_url()
{
	return THEME_URL;
}
function theme_folder()
{
	echo THEME_FOLDER;
}
function get_theme_folder()
{
	return THEME_FOLDER;
}
function zc_page_title()
{
	global $pagevars;
	echo $pagevars['title'];
}
function zc_get_page_title()
{
	global $pagevars;
	return $pagevars['title'];
}
function zc_page_content()
{
	global $pagevars;
	echo $pagevars['text'];
	
	if($pagevars['form'] != 0) {
		// Formulär
		zc_load_form($pagevars['form']);
		define("FORM_SUBMIT", false);
	
		?>
		<div class="zc_formTitle"><?PHP zc_form_title(); ?></div>
		<?PHP zc_form_content(); 
	}
}
function zc_get_page_content()
{
	global $pagevars;
	return $pagevars['text'];
}
function zc_page_meta($info)
{
	global $pagevars;
	echo $pagevars[$info];
}
function zc_get_page_meta($info)
{
	global $pagevars;
	return $pagevars[$info];
}
function zc_have_news()
{
	global $newsfeed;
	return $newsfeed->have_news();
}
function zc_news_title()
{
	global $newsfeed;
	$newsfeed->news_title();
}
function zc_get_news_title()
{
	global $newsfeed;
	return $newsfeed->get_news_title();
}
function zc_news_content($short = false, $length = 100)
{
	global $newsfeed;
	$newsfeed->news_content($short, $length);
}
function zc_get_news_content($short = false, $length = 100)
{
	global $newsfeed;
	return $newsfeed->get_news_content($short, $length);
}
function zc_news_date($format = "j/n - Y H:i")
{
	global $newsfeed;
	$newsfeed->news_date($format);
}
function zc_get_news_date($format = "j/n - Y H:i")
{
	global $newsfeed;
	return $newsfeed->get_news_date($format);
}
function zc_news_print_navigation()
{
	global $newsfeed;
	$newsfeed->print_navigation();
}
function zc_get_page($slug)
{
	$sql = mysql_query("SELECT * FROM pages WHERE slug = '$slug' LIMIT 1");
	if(mysql_num_rows($sql) == 1)
	{
		return mysql_fetch_assoc($sql);
	}
	else
	{
		return false;
	}
}
function zc_have_article()
{
	global $article;
	return $article->have_article();
}
function zc_article_title()
{
	global $article;
	$article->article_title();
}
function zc_get_article_title()
{
	global $article;
	return $article->get_news_title();
}
function zc_article_content($short = false, $length = 100)
{
	global $article;
	$article->article_content($short, $length);
}
function zc_get_article_content($short = false, $length = 100)
{
	global $article;
	return $article->get_article_content($short, $length);
}
function zc_article_date($format = "j/n - Y H:i")
{
	global $article;
	$article->article_date($format);
}
function zc_get_article_date($format = "j/n - Y H:i")
{
	global $article;
	return $article->get_article_date($format);
}
function zc_change_version_link($type)
{
	if($type == "normal")
	{
		echo HOST . "use_normal_version/" . str_replace("/", "|", LOCAL_PAGE);
	}
	elseif($type == "mobile")
	{
		echo HOST . "use_portable_version/" . str_replace("/", "|", LOCAL_PAGE);
	}
	else
	 return false;
}
function zc_get_change_version_link($type)
{
	if($type == "normal")
	{
		return HOST . "use_normal_version/" . str_replace("/", "|", LOCAL_PAGE);
	}
	elseif($type == "mobile")
	{
		return HOST . "use_portable_version/" . str_replace("/", "|", LOCAL_PAGE);
	}
	else
	 return false;
}
?>