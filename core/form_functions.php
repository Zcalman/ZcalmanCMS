<?PHP
/**
*	Formulär funktioner
**/

$FormObject = array();

function zc_load_form($formid = NULL) {
	global $FormObject;
	if($formid == NULL) {
		if(PAGE == "form") {
			$formid = safeText(OPT1);
			$formid = (int)$formid;
		}
		else
		{
			echo "<strong>ERROR:</strong> Missing form id";
			return false;
		}
	}
	else {
		$formid = safeText($formid);
		$formid = (int)$formid;
	}
	
	$getFormSql = mysql_query("SELECT * FROM `forms` WHERE `id` = " .$formid. " LIMIT 1");
	if($getFormSql == false || mysql_num_rows($getFormSql) == 0)
	{
		echo "<br /><strong>ERROR:</strong> No form found!";
		return false;
	}
	$FormObject['id'] = $formid;
	$r = mysql_fetch_assoc($getFormSql);
	$settings = explode("|", $r['settings']);
	for($i = 0; $i < count($settings); $i++) {
		$settings[$i] = explode(":", $settings[$i], 2);
		$settings[$settings[$i][0]] = $settings[$i][1];
		unset($settings[$i]);
	}
	$FormObject['settings'] = $settings;
	
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
	
	$FormObject['objects'] = $formObjects;
	return true;
}

function zc_form_title() {
	global $FormObject;
	if(!empty($FormObject))	{
		echo $FormObject['settings']['title'];
	}
}

function zc_get_form_content() {
	global $FormObject;
	
	if(!empty($FormObject))	{
		
		if(!defined("FORM_SUBMIT")) {
			return false;
		}
		
		$htmlReturn = "";
		
		if(FORM_SUBMIT) {
			$htmlReturn .= "<p class=\"zc_formSubmitMessage\">". $FormObject['settings']['message'] ."</p>";
		}
		else {
			$htmlReturn .= "<form id=\"{$FormObject['id']}\" class=\"zc_form\" action=\"" . HOST . "form/" . $FormObject['id'] . "/submit\" method=\"post\">";
			foreach($FormObject['objects'] as $obj) {
				$htmlReturn .= "<div id=\"zc_formObj-{$obj['id']}\" class=\"zc_formObj\"><h3>{$obj['title']}</h3>";
				switch($obj['type']) {
					case 'textfield': 
						$htmlReturn .= "<input type=\"text\" name=\"{$obj['title']}\" style=\"width:{$obj['settings']['width']};\" />";
					break;
					case 'textbox':
						$htmlReturn .= "<textarea name=\"{$obj['title']}\" style=\"width:{$obj['settings']['width']}; height:{$obj['settings']['height']};resize:none;\"></textarea>";
					break;
					case 'list':
						$htmlReturn .= "<select name=\"{$obj['title']}\" style=\"width:{$obj['settings']['width']};\">";
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
						$htmlReturn .= "<div class=\"zc_formRadioButtons\">";
						$options = explode("|", $obj['content']);
						foreach($options as $opt) {
							if(isset($obj['settings']['default']) && $opt == $obj['settings']['default']) {
								$htmlReturn .= "<input type=\"radio\" id=\"formRadio-{$obj['title']}-{$opt}\" name=\"{$obj['title']}\" checked=\"checked\" value=\"{$opt}\" /><label for=\"formRadio-{$obj['title']}-{$opt}\">{$opt}</label>";
							}
							else {
								$htmlReturn .= "<input type=\"radio\" id=\"formRadio-{$obj['title']}-{$opt}\" name=\"{$obj['title']}\" value=\"{$opt}\" /><label for=\"formRadio-{$obj['title']}-{$opt}\">{$opt}</label>";
							}
						}
						$htmlReturn .= "</div>";
					break;
					case 'checkbox':
						if(isset($obj['settings']['default']) && $obj['settings']['default'] == 'checked') {
							$htmlReturn .= "<input type=\"checkbox\" id=\"formCheckbox-{$obj['title']}\" checked=\"checked\" name=\"{$obj['title']}\" value=\"{$obj['content']}\" /><label for=\"formCheckbox-{$obj['title']}\">{$obj['content']}</label>";
						}
						else {
							$htmlReturn .= "<input type=\"checkbox\" id=\"formCheckbox-{$obj['title']}\" name=\"{$obj['title']}\" value=\"{$obj['content']}\" /><label for=\"formCheckbox-{$obj['title']}\">{$obj['content']}</label>";
						}
					break;
					default:
					break;
				}
				$htmlReturn.= "</div>";
			}
			$htmlReturn .= "<input type=\"submit\" />";
			$htmlReturn.= "</form>";
		}
		
		return $htmlReturn;
	}
}

function zc_form_content() {
	echo zc_get_form_content();
}

?>