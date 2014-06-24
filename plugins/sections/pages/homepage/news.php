<?PHP
global $pagevars;
$section = $pagevars['section'];
include("plugins/sections/SectionNewsFeed.class.php");
global $newsfeed;
$newsfeed = new SectionNewsFeed($section['id'], $section['slug']);	

$page = THEME_FOLDER . "news.php";
include($page);
?>