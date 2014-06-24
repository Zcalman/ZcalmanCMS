<?PHP
require("autoUpdateTop.php");

$menu = $_POST['menu'];
for ($i = 0; $i < count($menu); $i++) 
{
	mysql_query("UPDATE menu SET `sort` = " . $i . " WHERE `id` = '" . $menu[$i] . "'") or die(mysql_error());
}

require("autoUpdateBottom.php");
?>

stopLoad();