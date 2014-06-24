<?PHP
require("autoUpdateTop.php");
require("../pages.php");
$string = safeText(g('string'));
$id = (int)safeText(g('id'));
$slug = getSlug($string);
$finded = false;
$counter = 1;
do 
{
	$sql = mysql_query("SELECT id FROM pages WHERE slug = '{$slug}' AND id != {$id}");
	if(mysql_num_rows($sql) == 0)
	{
		if(!array_key_exists($slug, $pages))
		{
			$finded = true;
		}
		else
		{
			$slug = $slug . "-" . $counter;
			$counter++;
		}
	}
	else
	{
		$slug = $slug . "-" . $counter;
		$counter++;
	}
}while(!$finded);

require("autoUpdateBottom.php");
?>


var targetfield = document.getElementById("slug");
targetfield.value = "<?PHP echo $slug; ?>";

var targetDiv = document.getElementById("sluglink");
targetDiv.innerHTML = "<?PHP host(); echo $slug; ?>";