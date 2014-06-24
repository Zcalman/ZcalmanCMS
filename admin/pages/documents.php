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
	if($temp == "filetypedown")
	{
		$order = "type ASC";
		$afterlink = "filetypedown";
	}
	elseif($temp == "filetypeup")
	{
		$order = "type DESC";
		$afterlink = "filetypeup";
	}
	elseif($temp == "filenameup")
	{
		$order = "file DESC";
		$afterlink = "filenameup";
	}
	elseif($temp = "filenamedown")
	{
		$order = "file ASC";
		$afterlink = "filenamedown";
	}
	else
	{
		$order = "file ASC";
		$afterlink = "none";
	}
} 
else
{
	$order = "file ASC";
	$afterlink = "none";
}
$pageNav = new PageNav(15, 10, $pagenr, "documents", "goto", HOST . "admin/documents/", "/{$afterlink}", "ORDER BY {$order}");
$works = new Table(2, "Fil|Storlek", "642|150", "clickDocument");
while($r = mysql_fetch_assoc($pageNav->sqlres))
{
	$icon = "<img src='" . getFileIcon($r['type']) . "' alt='' style='margin-top:2px;' />";
	$size = getFileSize($r['size']);
	$works->addRow($r['id']."\",this,\"{$r['file']}\",\"{$size}", $icon . " {$r['file']}|{$size}");
}
?>
<div class="pagetitle">
Dokument
</div>
<div class="searchOrder" style="margin-bottom:20px;">
    <div class="dropbox">
        <select name="orderlist" class="dropdown" id="orderlist">
            <option <?PHP setSel($afterlink, "none"); ?>value="none">Sortera efter...</option>
            <option <?PHP setSel($afterlink, "filenamedown"); ?>value="filenamedown">Filnamn A-Ö</option>
            <option <?PHP setSel($afterlink, "filenameup"); ?>value="filenameup">Filnamn Ö-A</option>
            <option <?PHP setSel($afterlink, "filetypedown"); ?>value="filetypedown">Filtyp A-Ö</option>
            <option <?PHP setSel($afterlink, "filetypeup"); ?>value="filetypeup">Filtyp Ö-A</option>
        </select>
        <input type="hidden" value="all" id="showonlylist" />
    </div>
</div>
<div class="button right" onclick='javascript: opennewfileDialog();'>
    Ladda upp ett dokument
</div>
<?PHP
$works->printTable();
echo "\n";
$pageNav->printNav();
?>