<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
$query = mysql_query("SELECT * FROM `forms`");
$formsTable = new Table(3, "ID|Namn|Antal svar", "55|596|140", "clickForm");
while($r = mysql_fetch_assoc($query))
{
	$res = mysql_query("SELECT count(*) AS total FROM `forms_results` WHERE `formid` = {$r['id']}");
	$data = mysql_fetch_assoc($res);
	$settings = explode("|", $r['settings']);
	for($i = 0; $i < count($settings); $i++) {
		$settings[$i] = explode(":", $settings[$i], 2);
		$settings[$settings[$i][0]] = $settings[$i][1];
		unset($settings[$i]);
	}
	$formsTable->addRow($r['id'], "{$r['id']}|{$settings['title']}|{$data['total']}");
}
?>
<div class="pagetitle">
Formulär
</div>
<div class="button left" style="margin-bottom:10px;" onclick='javascript: goto(host + "admin/form");'>
    Skapa nytt formulär
</div>
<?PHP

$formsTable->printTable();
?>