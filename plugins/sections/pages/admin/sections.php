<?PHP
global $host;
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
if (defined("OPT3"))
{ 
	$pagenr = mysql_real_escape_string(trim(OPT3)); 
} 
else
{
	$pagenr = 1;
}
if (defined("OPT4"))
{ 
	$temp = mysql_real_escape_string(trim(OPT4)); 
	if($temp == "namedown")
	{
		$order = "name ASC";
		$afterlink = "namedown";
	}
	elseif($temp == "nameup")
	{
		$order = "name DESC";
		$afterlink = "nameup";
	}
	elseif($temp == "id")
	{
		$order = "id";
		$afterlink = "id";
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
$pageNav = new PageNav(15, 25, $pagenr, "sections", "goto", HOST . "admin/plugin/sections/", "/{$afterlink}", "ORDER BY {$order}");
$list = new Table(3, "ID|Namn|Länk", "55|266|470", "clickSection");
while($r = mysql_fetch_assoc($pageNav->sqlres))
{
	$list->addRow($r['id'], "{$r['id']}|{$r['name']}|" . HOST . "{$r['slug']}");
}
?>
<div class="pagetitle">
Sektioner
</div>
<div class="searchOrder" style="margin-bottom:20px;">
    <div class="dropbox">
        <select name="orderlist" class="dropdown" id="order-list">
        	<option <?PHP setSel($afterlink, "none"); ?>value="none">Sortera efter...</option>
            <option <?PHP setSel($afterlink, "id"); ?>value="id">Id</option>
            <option <?PHP setSel($afterlink, "namedown"); ?>value="namedown">Namn A-Ö</option>
            <option <?PHP setSel($afterlink, "nameup"); ?>value="nameup">Namn Ö-A</option>
        </select>
        <input type="hidden" value="all" id="showonlylist" />
    </div>
</div>
<div class="button right" onclick='javascript: goto(host + "admin/plugin/sections/section");'>
    Skapa ny sektion
</div>
<?PHP
$list->printTable();
echo "\n";
$pageNav->printNav();
?>