<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

rightsToSee(1,2);
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
	elseif($temp == "timedown")
	{
		$order = "date, time ASC";
		$afterlink = "timedown";
	}
	elseif($temp == "timeup")
	{
		$order = "id DESC";
		$afterlink = "timeup";
	}
	else
	{
		$order = "id DESC";
		$afterlink = "none";
	}
} 
else
{
	$order = "id DESC";
	$afterlink = "none";
}
$pageNav = new PageNav(15, 10, $pagenr, "news", "goto", HOST . "admin/news/", "/{$afterlink}", "ORDER BY {$order}");
$news = new Table(4, "ID|Rubrik|Datum|Tid", "40|580|110|60", "clickNews");
while($r = mysql_fetch_assoc($pageNav->sqlres))
{
	$date = splitToDate($r['date'], "j/n - Y");
	$time = splitToDate($r['date'] . $r['time'], "H:i");
	$news->addRow($r['id'], "{$r['id']}|{$r['title']}|{$date}|{$time}");
}
?>
<div class="pagetitle">
Nyheter
</div>
<div class="searchOrder" style="margin-bottom:20px;">
    <div class="dropbox">
    	<input type="hidden" value="all" id="showonlylist" />
        <select name="orderlist" class="dropdown" id="orderlist">
        	<option <?PHP setSel($afterlink, "none"); ?>value="none">Sortera efter...</option>
            <option <?PHP setSel($afterlink, "timeup"); ?>value="timeup">Tid/Datum Nyast</option>
            <option <?PHP setSel($afterlink, "timedown"); ?>value="timedown">Tid/Datum Äldst</option>
            <option <?PHP setSel($afterlink, "namedown"); ?>value="namedown">Rubrik A-Ö</option>
            <option <?PHP setSel($afterlink, "nameup"); ?>value="nameup">Rubrik Ö-A</option>
        </select>
    </div>
</div>
<div class="button right" onclick='javascript: goto(host + "admin/new");'>
	Skriv nyhet
</div>
<?PHP
$news->printTable();
echo "\n";
$pageNav->printNav();
?>