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
	$query = mysql_query("SELECT * FROM `forms_results` WHERE id = {$s} LIMIT 1");
	if($query == false || mysql_num_rows($query) == 0)
	{
		adminerror(404);
	}
	$r = mysql_fetch_assoc($query);
	$result = explode("|", $r['result']);
	for($i = 0; $i < count($result); $i++) {
		$result[$i] = explode(":", $result[$i], 2);
		$result[$result[$i][0]] = $result[$i][1];
		unset($result[$i]);
	}
	
	$query = mysql_query("SELECT * FROM `forms` WHERE id = {$r['formid']} LIMIT 1");
	$row = mysql_fetch_assoc($query);
	$settings = explode("|", $row['settings']);
	for($i = 0; $i < count($settings); $i++) {
		$settings[$i] = explode(":", $settings[$i], 2);
		$settings[$settings[$i][0]] = $settings[$i][1];
		unset($settings[$i]);
	}
	
	$_SESSION['return'] = $_SESSION['last_page'];
	
	?>
<div class="pagetitle">
Svar på formulär <em><?PHP echo $settings['title']; ?></em>
</div>
<div class="description">
<strong>Svar ID:</strong> <?PHP echo $r['id']; ?><br />
<strong>Formulär ID:</strong> <?PHP echo $r['formid']; ?><br />
<strong>IP-adress:</strong> <?PHP echo $r['ip']; ?><br />
<strong>Datum & tid:</strong> <?PHP echo $r['datetime']; ?><br />
</div>
<table class="formResultTable">
	<?PHP 
	foreach($result as $key => $value) {
		?>
    <tr>
        <td>
           <?PHP echo $key; ?>
        </td>
        <td>
           <?PHP echo $value; ?>
        </td>
    </tr>
		<?PHP
	}
	?>
</table>

<div class="button left" onclick='javascript: goto("<?PHP echo $_SESSION['return']; ?>");' style="clear:both;">
    Tillbaka
</div>
<div class="button right" onclick='securityAskDeleteFormResult("<?PHP echo $settings['title'] . '","' . $r['id'];?>")'>
    Radera
</div>
<?PHP

}
else {
	adminerror(404);
}

?>