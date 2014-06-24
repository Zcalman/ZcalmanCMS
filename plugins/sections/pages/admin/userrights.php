<?PHP
global $host;
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
if (defined("OPT3"))
{ 
	$section_id = mysql_real_escape_string(trim(OPT3));
} 
else
{
	$section_id = 0;
}
if (defined("OPT4"))
{ 
	$pagenr = mysql_real_escape_string(trim(OPT4)); 
} 
else
{
	$pagenr = 1;
}

if($section_id != 0)
{
	$pageNav = new PageNav(15, 25, $pagenr, "sections_userrights", "goto", HOST . "admin/plugin/sections/userrights/", "", "WHERE section_id = {$section_id}");
	$works = new Table(3, "Namn|Användarnamn|Ta bort", "450|266|75", "clickSectionUser");
	while($r = mysql_fetch_assoc($pageNav->sqlres))
	{
		$query = mysql_query("SELECT username, name FROM userbase WHERE id = {$r['userid']} LIMIT 1");
		if(mysql_num_rows($query) == 0)
		{
			$sql = mysql_query("DELETE FROM sections_userrights WHERE id = {$r['id']} LIMIT 1");
		}
		else
		{
			$u = mysql_fetch_assoc($query);
			$works->addRow($r['id'], "{$u['name']}|{$u['username']}|<img src='". HOST . "admin/images/icons/delete.png' alt='Ta bort' onclick='javascript: sections_delete_rights(". $r['id'] .",\"". $u['name'] . "\",". $section_id .")' style='border:0px; margin-left:26px; margin-top:2px;' />");
		}
	}
	?>
	<?PHP if(defined("OPT5"))
    {
        echo "<div class=\"messagebox\">" . safeText(OPT5) . "</div>";
    }
    ?>
	<div class="pagetitle">
        Användare i sektionen <?PHP echo sections_getSectionName($section_id); ?>
        <div class="button right" style="margin-top:5px;" onclick='javascript: goto(host + "admin/plugin/sections/userrights/");'>
            Välj en annan sektion
        </div>
	</div>
	<div class="searchOrder" style="margin-bottom:20px;">
	</div>
    <div class="button right" style="margin-bottom:10px;" onclick='sections_new_right()'>
        Lägg till användare
    </div>
	<?PHP
	$works->printTable();
	echo "\n";
	$pageNav->printNav();
	?>
    <div class="hidden">
        <div id="new_right_dialog" title="Lägg till användare">
            <p>Välj en användare som ska få tillgång till sektionen.</p>
            <form name="new_right_form" id="new_right_form" action="<?PHP echo HOST . "admin/plugin/sections/save/rights/"; ?>" method="post">
        		<select name="user" id="user" style="margin-top:10px; float:left; width:300px;">
                	<option value="0">Välj användare...</option>
				<?PHP
				$sql = mysql_query("SELECT * FROM userbase WHERE userclass = 2");
				while($s = mysql_fetch_assoc($sql))
				{
					?>
					<option value="<?PHP echo $s['id']; ?>"><?PHP echo $s['name']; ?> (<?PHP echo $s['username']; ?>)</option>
					<?PHP
				}
				?>
        		</select>
    			<input type="hidden" value="<?PHP echo $section_id; ?>" id="sectionid" name="sectionid" />
            </form>
        </div> 
        <div id="delete_right_dialog" title="Säkerhetsfråga">Är du säker på att du vill ta bort <span style="font-weight:bold;" id="delete_rightsname"></span> från sektionen?</div>
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
    	Välj i vilken sektion du vill hantera användare.
    </div>
    <div class="dropbox">
        <select name="select_section" class="dropdown" id="select_section">
        	<?PHP
			$sql = mysql_query("SELECT * FROM sections");
			while($s = mysql_fetch_assoc($sql))
			{
				?>
				<option value="<?PHP echo $s['id']; ?>"><?PHP echo $s['name']; ?></option>
				<?PHP
			}
			?>
        </select>
    </div>
    <input type="hidden" value="userrights" id="select_section_page" />
    <input type="submit" id="select_section_submit" class="submitbutton" value="Välj sektion">
    <?PHP
}
?>