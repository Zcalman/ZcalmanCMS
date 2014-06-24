<?PHP
/**
*	Handle form requests
**/
if(defined("OPT1")) {
	$formid = safeText(OPT1);
	if(empty($formid)) {
		code_error(404);
	}
	$formid = (int)$formid;
	if(defined("OPT2") && OPT2 == "submit") {
		if(!empty($_POST)) {
			define("FORM_SUBMIT", true);
			$result = "";$mailresult = "";
			$sql = mysql_query("SELECT * FROM `forms_objects` WHERE `formid` = ". $formid ." ORDER BY `sort`");
			while($r = mysql_fetch_assoc($sql)) {
				$title = str_replace(" ", "_", $r['title']);
				if(isset($_POST[$title])) {
					$result .= $r['title'] .":". settingSafeText($_POST[$title]) ."|";
					$mailresult .= "<p><strong>". $r['title'] ."</strong><br />". safeText($_POST[$title]) ."</p>";
				}
				else {
					$result .= $r['title'] .":|";
					$mailresult .= "<p><strong>". $r['title'] ."</strong><br /></p>";
				}
			}
			$result = substr_replace($result, "", -1);
			
			$ins = mysql_query("INSERT INTO `forms_results` (`formid`,`result`,`ip`) VALUES (". $formid .", '". $result ."', '". $_SERVER['REMOTE_ADDR'] ."')");
			
			$result = mysql_query("SELECT * FROM `forms` WHERE `id` = " .$formid. " LIMIT 1");
			$r = mysql_fetch_assoc($result);
			$settings = explode("|", $r['settings']);
			for($i = 0; $i < count($settings); $i++) {
				$settings[$i] = explode(":", $settings[$i], 2);
				$settings[$settings[$i][0]] = $settings[$i][1];
				unset($settings[$i]);
			}
			if(isset($settings['emailto'])) {
				if (filter_var($settings['emailto'], FILTER_VALIDATE_EMAIL)) {
					
					// Skickar mail om att något svarat på formuläret
					// Sätter typ av radbrytning, används i bl.a. headers i mail
					if(strtoupper(substr(PHP_OS,0,3)=='WIN')) { 
					  $eol = "\r\n"; 
					}
					elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { 
					  $eol = "\r"; 
					}
					else { 
					  $eol = "\n"; 
					}
					
					global $server_mail, $page_title, $support_mail;

					$to = $settings['emailto'];
					$from = $server_mail;
					$subject = "Svar på formuläret ". $settings['title'];
					$srvname = $page_title . " - Server";
					$headers = "MIME-Version: 1.0{$eol}";
					$headers .= "Content-type: text/html; charset=utf-8{$eol}";
					$headers .= "Content-Transfer-Encoding: 8bit{$eol}";
					$headers .= "From: $srvname <{$from}> {$eol}"; 
					$headers .= "Reply-To: Support <{$support_mail}> {$eol}"; 
					$text = "<html><body><h1><strong>Nytt svar på formuläret: ". $settings['title'] ."</strong></h1>
					<p>Nedan syns resultatet.<br />Alla resultat går att se i kontrollpanelen.</p>";
					$text .= $mailresult;
					$text .= "</body></html>";
				
					mail($to, $subject, $text , $headers);
					
				}
			}
			
		}
		else {
			define("FORM_SUBMIT", false);
		}
	}
	else {
		define("FORM_SUBMIT", false);
	}
	
	
	$page = THEME_FOLDER . "form.php";
	if(file_exists($page))
	{
		include($page);
	}
	else 
	{
		zc_load_form();

		?>
		<h1>Formulär: <?PHP zc_form_title(); ?></h1>
		<?PHP zc_form_content(); 
	}
}
else
{
	code_error(404);
}




?>