<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
if (defined("OPT1"))
{ 
	$pagenr = mysql_real_escape_string(trim(OPT1)); 
} 
else
{
	$pagenr = 1;
}
if (defined("OPT2"))
{ 
	$temp = mysql_real_escape_string(trim(OPT2)); 
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
$pageNav = new PageNav(15, 10, $pagenr, "pages", "goto", HOST . "admin/pages/", "/{$afterlink}", "ORDER BY {$order}");
$works = new Table(3, "ID|Rubrik|Länk", "55|266|470", "clickPage");
while($r = mysql_fetch_assoc($pageNav->sqlres))
{
	$works->addRow($r['id'], "{$r['id']}|{$r['title']}|{$host}{$r['slug']}");
}
?>
<div class="pagetitle">
Sidor
</div>
<div class="searchOrder" style="margin-bottom:20px;">
    <div class="dropbox">
        <select name="orderlist" class="dropdown" id="orderlist">
        	<option <?PHP setSel($afterlink, "none"); ?>value="none">Sortera efter...</option>
            <option <?PHP setSel($afterlink, "id"); ?>value="id">Id</option>
            <option <?PHP setSel($afterlink, "namedown"); ?>value="namedown">Namn A-Ö</option>
            <option <?PHP setSel($afterlink, "nameup"); ?>value="nameup">Namn Ö-A</option>
        </select>
        <input type="hidden" value="all" id="showonlylist" />
    </div>
</div>
<div class="button right" onclick='javascript: goto(host + "admin/page");'>
    Skapa ny sida
</div>
<?PHP
$works->printTable();
echo "\n";
$pageNav->printNav();
?>