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
if (defined("OPT4"))
{ 
	$pagenr = mysql_real_escape_string(trim(OPT4)); 
} 
else
{
	$pagenr = 1;
}
if (defined("OPT5"))
{ 
	$temp = mysql_real_escape_string(trim(OPT5)); 
	if($temp == "namedown")
	{
		$order = "title ASC";
		$afterlink = "namedown";
	}
	elseif($temp == "nameup")
	{
		$order = "title DESC";
		$afterlink = "nameup";
	}
	elseif($temp == "userid")
	{
		$order = "id";
		$afterlink = "userid";
	}
	else
	{
		$order = "id";
		$afterlink = "none";
	}
} 
else
{
	$order = "id";
	$afterlink = "none";
}
if($section_id != 0)
{
	$pageNav = new PageNav(15, 25, $pagenr, "sections_pages", "goto", HOST . "admin/plugin/sections/sectionpages/{$section_id}/", "/{$afterlink}", "WHERE extrawide = 0 AND section_id = {$section_id} ORDER BY {$order}");
	$works = new Table(3, "ID|Rubrik|Länk", "55|266|470", "clickSectionPage");
	while($r = mysql_fetch_assoc($pageNav->sqlres))
	{
		$works->addRow($r['id'], "{$r['id']}|{$r['title']}|" . HOST . sections_getSectionSlug($section_id) . "/{$r['slug']}");
	}
	?>
	<div class="pagetitle">
        Sidor i sektionen <?PHP echo sections_getSectionName($section_id); ?>
        <?PHP
        if((!is_admin() && count($_SESSION['sections_rights']) > 1) || is_admin())
		{
			?>
			<div class="button right" style="margin-top:5px;" onclick='javascript: goto(host + "admin/plugin/sections/sectionpages/");'>
				Välj en annan sektion
			</div>
			<?PHP
		}
		?>
	</div>
	<div class="searchOrder" style="margin-bottom:20px;">
		<div class="dropbox">
			<select name="orderlist" class="dropdown" id="order-pages-list">
				<option <?PHP setSel($afterlink, "none"); ?>value="none">Sortera efter...</option>
				<option <?PHP setSel($afterlink, "id"); ?>value="id">Id</option>
				<option <?PHP setSel($afterlink, "namedown"); ?>value="namedown">Namn A-Ö</option>
				<option <?PHP setSel($afterlink, "nameup"); ?>value="nameup">Namn Ö-A</option>
			</select>
		</div>
        <input type="hidden" value="all" id="showonlylist" />
        <input type="hidden" value="<?PHP echo $section_id; ?>" id="sectionid" />
	</div>
	<div class="button right" onclick='javascript: goto(host + "admin/plugin/sections/sectionpage/<?PHP echo $section_id; ?>");'>
		Skapa ny sida
	</div>
	<?PHP
	$works->printTable();
	echo "\n";
	$pageNav->printNav();
}
else
{
	?>
	<div class="pagetitle">
		Välj en sektion
	</div>
    <div class="description" style="margin-bottom:20px;">
    	Eftersom du kan hantera sidor i flera olika sektioner måste du välja den sektion <br />
        som du vill hantera sidorna för.
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
    <input type="hidden" value="sectionpages" id="select_section_page" />
    <input type="submit" id="select_section_submit" class="submitbutton" value="Gå till sektionen">
    <?PHP
}
?>