<?PHP
global $host;
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
if (defined("OPT3"))
{ 
	$section_id = mysql_real_escape_string(trim(OPT3)); 
	if(!is_admin())
	{
		if(!in_array($section_id, $_SESSION['sections_rights']))
		{
			adminerror(403);
		}
	}
} 
else
{
	$section_id = 0;
	if(!is_admin())
	{
		if(count($_SESSION['sections_rights']) == 1)
		{
			$section_id = $_SESSION['sections_rights'][0];
		}
	}
}
?>

<?PHP
if($section_id != 0)
{
	?>
	<link href="<?PHP host(); ?>plugins/sections/pages/admin/style.css" rel="stylesheet" type="text/css" />
    <div class="pagetitle">
    Menyhantering i sektionen <?PHP echo sections_getSectionName($section_id); ?>
        <?PHP
        if((!is_admin() && count($_SESSION['sections_rights']) > 1) || is_admin())
		{
			?>
			<div class="button right" style="margin-top:5px;" onclick='javascript: goto(host + "admin/plugin/sections/menus/");'>
				Välj en annan sektion
			</div>
			<?PHP
		}
		?>
    </div>
    <div class="description">Dra de olika menylänkarna i den ordning du vill att de ska synas på hemsidan.<br />
    För att lägga till en undermeny och ändra själva huvudmenyslänken, klicka på pennan.</div>
    
    <div style="float:left; width:300px;">
    <ul id="sectionmenu_sort">
	<?PHP
	// Tar fram listan från Databasen
	$result = mysql_query("SELECT * FROM sections_menu WHERE parent = 0 AND section_id = {$section_id} ORDER BY `sort` ASC") or die(mysql_error());
	while($row = mysql_fetch_array($result)) 
	{
		echo "<li id='menu_" . $row['id'] . "'>{$row['title']}";
		if($row['active'] == 0)
		{
			echo " <span class='italic table-description'>(Ej aktiv)</span>";
		}
		echo "<img src='". HOST . "admin/images/icons/pencil.png' alt='Editera meny' onclick='javascript: goto(\"". HOST . "admin/plugin/sections/menu/". $section_id ."/". $row['id'] ."\");' style='border:0px;' /></li>";
	}
	?>
	</ul>
	<div class="button nomargin" onclick='javascript: goto(host + "admin/plugin/sections/menu/<?PHP echo $section_id; ?>");'>
		Ny menylänk
	</div>
	</div>
	<?PHP
}
else
{
	?>
	<div class="pagetitle">
		Välj en sektion
	</div>
    <div class="description" style="margin-bottom:20px;">
    	Eftersom du kan hantera menyer i flera olika sektioner måste du välja den sektion <br />
        som du vill hantera menyn för.
    </div>
    <div class="dropbox">
        <select name="select_section" class="dropdown" id="select_section">
        	<?PHP
			if(!is_admin())
			{
				foreach($_SESSION['sections_rights'] as $secid)
				{
					?>
					<option value="<?PHP echo $secid; ?>"><?PHP echo sections_getSectionName($secid); ?></option>
					<?PHP
				}
			}
			else
			{
				$sql = mysql_query("SELECT * FROM sections");
				while($s = mysql_fetch_assoc($sql))
				{
					?>
					<option value="<?PHP echo $s['id']; ?>"><?PHP echo $s['name']; ?></option>
					<?PHP
				}
			}
			?>
        </select>
    </div>
    <input type="hidden" value="menus" id="select_section_page" />
    <input type="submit" id="select_section_submit" class="submitbutton" value="Gå till sektionen">
    <?PHP
}
?>