<?PHP
// Awesome code goes here
global $pagevars;
if($pagevars['type'] == "html")
{
	$page = THEME_FOLDER . "page.php";
	include($page);
}
elseif($pagevars['type'] == "iframe")
{
	$page = THEME_FOLDER . "iframepage.php";
	include($page);
}
?>