<?PHP
/**
*	DESC
**/
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
$formid = "Nytt formulär";
$title = "Titel";
$submitmessage = "";
$emailto = "";
$oldForm = false;
$htmlReturn = "";
$totalResults = 0;
if(defined("OPT1"))
{
	$s = safeText(OPT1);
	$s = (int)$s;
	$query = mysql_query("SELECT * FROM forms WHERE id = {$s} LIMIT 1");
	if($query == false || mysql_num_rows($query) == 0)
	{
		adminerror(404);
	}
	$r = mysql_fetch_assoc($query);
	$res = mysql_query("SELECT count(*) AS total FROM `forms_results` WHERE `formid` = {$r['id']}");
	$data = mysql_fetch_assoc($res);
	$totalResults = $data['total'];
	$settings = explode("|", $r['settings']);
	for($i = 0; $i < count($settings); $i++) {
		$settings[$i] = explode(":", $settings[$i], 2);
		$settings[$settings[$i][0]] = $settings[$i][1];
		unset($settings[$i]);
	}
	$formid = $r['id'];
	$title = $settings['title'];
	if(isset($settings['emailto'])) 
	{
		$emailto = $settings['emailto'];
	}
	$submitmessage = $settings['message'];
	$oldForm = true;
	
	// Get form objects
	$formObjects = array();
	// Get form objects from database
	$result = mysql_query("SELECT * FROM `forms_objects` WHERE `formid` = {$formid} ORDER BY `sort`")or exit(mysql_error());
	while($row = mysql_fetch_assoc($result)) {
		$row['settings'] = explode("|", $row['settings']);
		for($i = 0; $i < count($row['settings']); $i++) {
			$row['settings'][$i] = explode(":", $row['settings'][$i], 2);
			if(isset($row['settings'][$i][1])) {
				$row['settings'][$row['settings'][$i][0]] = $row['settings'][$i][1];
			}
			unset($row['settings'][$i]);
			
		}
		$formObjects[] = $row;
	}
	
	
	foreach($formObjects as $obj) {
		$htmlReturn .= "<div id=\"formObj-{$obj['id']}\" class=\"formObj\" type=\"{$obj['type']}\"><h3>{$obj['title']}</h3>";
		switch($obj['type']) {
			case 'textfield': 
				$htmlReturn .= "<input type=\"text\" name=\"{$obj['title']}\" disabled=\"disabled\" style=\"width:{$obj['settings']['width']};\" />";
			break;
			case 'textbox':
				$htmlReturn .= "<textarea disabled=\"disabled\" name=\"{$obj['title']}\" style=\"width:{$obj['settings']['width']}; height:{$obj['settings']['height']};resize:none;\"></textarea>";
			break;
			case 'list':
				$htmlReturn .= "<select name=\"{$obj['title']}\" disabled=\"disabled\" style=\"width:{$obj['settings']['width']};\">";
				$options = explode("|", $obj['content']);
				foreach($options as $opt) {
					if(isset($obj['settings']['default']) && $opt == $obj['settings']['default']) {
						$htmlReturn .= "<option selected=\"selected\" value=\"{$opt}\">{$opt}</option>";
					}
					else {
						$htmlReturn .= "<option value=\"{$opt}\">{$opt}</option>";
					}
				}
				$htmlReturn .= "</select>";
			break;
			case 'radio':
				$htmlReturn .= "<div class=\"formRadioButtons\">";
				$options = explode("|", $obj['content']);
				foreach($options as $opt) {
					if(isset($obj['settings']['default']) && $opt == $obj['settings']['default']) {
						$htmlReturn .= "<input type=\"radio\" disabled=\"disabled\" id=\"formRadio-{$obj['title']}-{$opt}\" name=\"{$obj['title']}\" checked=\"checked\" value=\"{$opt}\" /><label for=\"formRadio-{$obj['title']}-{$opt}\">{$opt}</label>";
					}
					else {
						$htmlReturn .= "<input type=\"radio\" disabled=\"disabled\" id=\"formRadio-{$obj['title']}-{$opt}\" name=\"{$obj['title']}\" value=\"{$opt}\" /><label for=\"formRadio-{$obj['title']}-{$opt}\">{$opt}</label>";
					}
				}
				$htmlReturn .= "</div>";
			break;
			case 'checkbox':
				if(isset($obj['settings']['default']) && $obj['settings']['default'] == 'checked') {
					$htmlReturn .= "<input type=\"checkbox\" disabled=\"disabled\" id=\"formCheckbox-{$obj['title']}\" checked=\"checked\" name=\"{$obj['title']}\" value=\"{$obj['content']}\" /><label for=\"formCheckbox-{$obj['title']}\">{$obj['content']}</label>";
				}
				else {
					$htmlReturn .= "<input type=\"checkbox\" disabled=\"disabled\" id=\"formCheckbox-{$obj['title']}\" name=\"{$obj['title']}\" value=\"{$obj['content']}\" /><label for=\"formCheckbox-{$obj['title']}\">{$obj['content']}</label>";
				}
			break;
			default:
				$htmlReturn .= "Unkown type";
			break;
		}
		$htmlReturn.= "</div>";
	}
		
}

$_SESSION['return'] = $_SESSION['last_page'];
if(defined("OPT2"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT2) . "</div>";
}
?>
<div class="pagetitle">
Formulär
</div>
<?PHP 
if($oldForm)
{	
	?>
    <input type="button" class="submitbutton left" style="margin-bottom:20px;" value="Visa svar (<?PHP echo $totalResults; ?>)" onclick='javascript: goto("<?PHP adminhost(); echo "form_results/" . $formid; ?>")'>
    <?PHP
}
?>
<input type="hidden" value="<?PHP echo $formid; ?>" id="formid" name="formid" />
<input tabindex="1" type="text" class="textfield large-textfield" id="title" name="title" onfocus='javascript:falt(this,this.value,"Titel");' onblur='javascript:tom(this,this.value,"Titel");' onchange='javascript: setChanged(this);' value="<?PHP echo $title; ?>" />
<table class="settingstable">
    <tr>
        <td class="settingstitle">
            Om formuläret ska resultera i ett mail, ange e-postadressen här<br /><span class="italic table-description">(Annars lämna fältet tomt. Endast en e-postadress)</span>
        </td>
        <td class="settingsfield">
    		<input tabindex="2" type="text" class="textfield" name="email" id="email" style="width:300px;" onchange="javascript: setChanged(this);" value="<?PHP echo $emailto; ?>" />
        </td>
    </tr>
    <tr>
        <td class="settingstitle">
            Svarsmeddelande<br /><span class="italic table-description">(Visas för besökaren när förmuläret är skickat)</span>
        </td>
        <td class="settingsfield">
    		<input tabindex="2" type="text" class="textfield" name="submitmessage" id="submitmessage" style="width:300px;" onchange="javascript: setChanged(this);" value="<?PHP echo $submitmessage; ?>" />
        </td>
    </tr>
</table>
<h2 style="margin-top:20px;">Lägg till</h2>
<?PHP
if($totalResults > 0)
{
	echo "<div class=\"messagebox\" style=\"float:left;\">VARNING! Ändringar kan medföra att insamlade svar kan bli inaktuella.</div>";
}
?>
<div class="button left" onclick='javascript:addFormObject("textfield");'>Textfält</div>
<div class="button left" onclick='javascript:addFormObject("textbox");'>Textruta</div>
<div class="button left" onclick='javascript:addFormObject("list");'>Lista</div>
<div class="button left" onclick='javascript:addFormObject("radio");'>Radioknappsgrupp</div>
<div class="button left" onclick='javascript:addFormObject("checkbox");'>Checkbox</div>
<div id="form_sort">
<?PHP
echo $htmlReturn;
?>
</div>
<div style="width:100%; float:left;">
<input tabindex="6" type="button" value="Spara" class="submitbutton" style="cursor:pointer;" onclick="javascript: saveForm();"/>
<?PHP if($oldForm)
{
	?>
    <div class="button right" onclick='securityAskDeleteForm("<?PHP echo $title . '","' . $formid;?>")'>
        Radera formulär
    </div>
    <?PHP
}
?>
</div>

<script type="text/javascript" src="<?PHP host(); ?>admin/script/formscript.js"></script>