<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
?>
<div class="pagetitle">
Huvudmenyhantering
</div>
<div class="description">Dra de olika menylänkarna i den ordning du vill att de ska synas på hemsidan.<br />
För att lägga till en undermeny och ändra själva huvudmenyslänken, klicka på pennan.</div>

<div style="float:left; width:300px;">
<ul id="mainmenu_sort">
<?PHP
rightsToSee(1);
// Tar fram listan från Databasen
$result = mysql_query("SELECT * FROM menu WHERE parent = 0 ORDER BY `sort` ASC") or die(mysql_error());
while($row = mysql_fetch_array($result)) 
{
	echo "<li id='menu_" . $row['id'] . "'>{$row['title']}";
	if($row['active'] == 0)
	{
		echo " <span class='italic table-description'>(Ej aktiv)</span>";
	}
	echo "<img src='". HOST . "admin/images/icons/pencil.png' alt='Editera meny' onclick='javascript: goto(\"". HOST . "admin/menu/". $row['id'] ."\");' style='border:0px;' /></li>";
}
?>
</ul>
<div class="button nomargin" onclick='javascript: goto(host + "admin/menu");'>
    Ny huvudmenylänk
</div>
</div>
