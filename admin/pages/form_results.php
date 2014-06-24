<?PHP
/**
*	DESC
**/
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
if(defined("OPT1"))
{
	$s = safeText(OPT1);
	$s = (int)$s;
	$query = mysql_query("SELECT * FROM `forms` WHERE id = {$s} LIMIT 1");
	if($query == false || mysql_num_rows($query) == 0)
	{
		adminerror(404);
	}
	$row = mysql_fetch_assoc($query);
	$settings = explode("|", $row['settings']);
	for($i = 0; $i < count($settings); $i++) {
		$settings[$i] = explode(":", $settings[$i], 2);
		$settings[$settings[$i][0]] = $settings[$i][1];
		unset($settings[$i]);
	}
	if (defined("OPT2"))
	{ 
		$pagenr = mysql_real_escape_string(trim(OPT2)); 
	} 
	else
	{
		$pagenr = 1;
	}
	if (defined("OPT3"))
	{ 
		$temp = mysql_real_escape_string(trim(OPT3)); 
		if($temp == "timedown")
		{
			$order = "`datetime` ASC";
			$afterlink = "timedown";
		}
		elseif($temp == "timeup")
		{
			$order = "`datetime` DESC";
			$afterlink = "timeup";
		}
		else
		{
			$order = "`datetime` DESC";
			$afterlink = "timeup";
		}
	} 
	else
	{
		$order = "`datetime` DESC";
		$afterlink = "timeup";
	}
	$pageNav = new PageNav(25, 10, $pagenr, "`forms_results`", "goto", HOST . "admin/form_results/". $row['id'] ."/", "/{$afterlink}", "WHERE `formid` = {$row['id']} ORDER BY {$order}");
	$forms_results = new Table(3, "Tid|IP|Svar", "200|150|441", "clickFormResult");
	while($r = mysql_fetch_assoc($pageNav->sqlres))
	{
		$forms_results->addRow($r['id'], "{$r['datetime']}|{$r['ip']}|{$r['result']}");
	}
	?>
<div class="pagetitle">
Svar på formulär <em><?PHP echo $settings['title']; ?></em>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#orderlistForm').change(function() {
		var tempval = $('#showonlylist').val();
		goto(adminhost + page + "/<?PHP echo $row['id']; ?>/1/" + this.value + "/" + tempval);
	});
});
</script>
<div class="searchOrder" style="margin-bottom:20px;">
    <div class="dropbox">
    	<input type="hidden" value="all" id="showonlylist" />
        <select name="orderlistForm" class="dropdown" id="orderlistForm">
            <option <?PHP setSel($afterlink, "timeup"); ?>value="timeup">Tid/Datum Nyast först</option>
            <option <?PHP setSel($afterlink, "timedown"); ?>value="timedown">Tid/Datum Äldst först</option>
        </select>
    </div>
</div>
	<?PHP
$forms_results->printTable();
echo "\n";
$pageNav->printNav();

}
else {
	adminerror(404);
}
?>