<?PHP
global $pagevars;
$section = $pagevars['section'];
include("plugins/sections/SectionArticle.class.php");
global $article;
$article = new SectionArticle($section['id'], $section['slug']);	

$page = THEME_FOLDER . "article.php";
include($page);
?>