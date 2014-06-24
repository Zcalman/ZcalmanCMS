<?PHP
// Ett simpelt plugin

### Körs vid installation
function calendar_install()
{
	if(!calendar_table_exists("zc_calendar"))
	{
		$query = "CREATE TABLE `zc_calendar` (`id` int(11) NOT NULL AUTO_INCREMENT, `text` text NOT NULL, `title` text NOT NULL, `year` int(11) NOT NULL, `month` int(11) NOT NULL, `day` int(11) NOT NULL, `date` int(11) NOT NULL, UNIQUE KEY `id` (`id`) );";
		$sql = mysql_query($query);
	}
}

### Körs vid avinstallation
function calendar_uninstall()
{
	if(calendar_table_exists("zc_calendar"))
	{
		$query = "DROP TABLE `zc_calendar`";
		$sql = mysql_query($query);
	}
}

### Körs vid aktivering
function calendar_activate()
{
	
}

### Körs vid avaktivering
function calendar_inactivate()
{
	
}

### Körs på varje sida på hemsidan när pluginet läses in
function calendar_init_page()
{
	//zc_add_page("forum", "forum.php", "Forum");
}

### Körs på varje sida i administrationspanelen när pluginet läses in
function calendar_init_admin()
{
	zc_add_menu("zc_calendar_menu", "Kalender");
	zc_add_menulink("zc_calendar_menu", "Hantera aktiviteter", "plugin/calendar/");
	zc_add_menulink("zc_calendar_menu", "Ny aktivitet", "plugin/calendar/activity");
}

### Körs när man går in på pluginet i adminpanelen (http://website.com/admin/plugin/yourplugin)
function calendar_show_adminpage()
{
	$page = "start";
	if(defined("OPT2"))
	{
		$page = safeText(OPT2);
	}
	
	switch($page)
	{
		case "start":
			$sql = mysql_query("SELECT * FROM zc_calendar WHERE date >= ". date("Ymd") ." ORDER BY  date, title");
			$activities = new Table(3, "Rubrik|Text|Datum", "325|296|170", "clickActivity");
			while($r = mysql_fetch_assoc($sql))
			{
				$activities->addRow($r['id'], "{$r['title']}|" . cutText($r['text'], 40) . "|{$r['year']}-{$r['month']}-{$r['day']}");
			}
			
			echo "<script src='" . PLUGINFOLDER . "script.js' type='text/javascript'></script>";
			echo "<div class='pagetitle'>Hantera aktiviteter</div>";
			echo "<div class='description'>Här visas alla aktiviteter från och med idag och framåt.<br />Klicka på den aktivitet du vill ändra.<br /><a href='". HOST ."admin/plugin/calendar/history'>Vill du se äldre aktiviteter, klicka här.</a></div>";
			?>
            <div class="button right" onclick='javascript: goto(host + "admin/plugin/calendar/activity");' style="margin-bottom:20px;">
                Skapa ny aktivitet
            </div>
            <?PHP
			$activities->printTable();
			break;
		case "activity":
			$act_id = "Ny aktivitet";
			$text = "";
			$title = "";
			$date = "";
			$oldAct = false;
			$verb = "Skapa";
			if(defined("OPT3"))
			{
				$s = safeText(OPT3);
				$s = (int)$s;
				$query = mysql_query("SELECT * FROM zc_calendar WHERE id = {$s} LIMIT 1");
				if($query == false || mysql_num_rows($query) == 0)
				{
					adminerror(404);
				}
				$r = mysql_fetch_assoc($query);
				$act_id = $r['id'];
				$text = $r['text'];
				$title = $r['title'];
				$date = $r['year'] . "-" . fixZero($r['month']) . "-" . fixZero($r['day']);
				$oldAct = true;
				$verb = "Ändra";
			}
				
			if(defined("OPT4"))
			{
				echo "<div class=\"messagebox\">" . safeText(OPT4) . "</div>";
			}
			echo "<script src='" . PLUGINFOLDER . "script.js' type='text/javascript'></script>";
			?>
			<form action="<?PHP host(); ?>admin/plugin/calendar/save" <?PHP if(!$oldAct){ echo 'enctype="multipart/form-data"'; } ?> method="post" id="customerform">
            <div class="pagetitle"><?PHP echo $verb; ?> aktivitet</div>
            <input type="hidden" id="act_id" name="act_id" value="<?PHP echo $act_id; ?>" />
            <table class="settingstable">                
                <tr>
                    <td class="settingstitle">
                        Datum
                    </td>
                    <td class="settingsfield">
                        <input tabindex="1" type="text" class="textfield" id="date" name="date" onchange='javascript: setChanged(customerform);' value="<?PHP echo $date; ?>" style="width:100px; min-width:100px;" />
                    </td>
                </tr>
                <tr>
                    <td class="settingstitle">
                        Rubrik
                    </td>
                    <td class="settingsfield">
                        <input tabindex="2" type="text" class="textfield" id="title" name="title" onchange='javascript: setChanged(customerform);' value="<?PHP echo $title; ?>" style="width:400px;" />
                    </td>
                </tr>
                <tr>
                    <td class="settingstitle">
                        Text
                    </td>
                    <td class="settingsfield">
                        <textarea tabindex="3" class="textfield" id="text" name="text" onchange='javascript: setChanged(customerform);' style="width:400px; height:50px;" /><?PHP echo $text; ?></textarea>
                    </td>
                </tr>
            </table>
            <input tabindex="4" type="submit" value="Spara" class="submitbutton" style="cursor:pointer;" />
            <?PHP
            if($oldAct)
            {
				$_SESSION['return'] = HOST . "admin/plugin/calendar/";
                ?>
                <div class="hidden">
                	<div id="zc_calendar_delete_act_dialog" title="Säkerhetsfråga">Är du säker på att du vill ta bort den här aktiviteten?</div>
                </div>
                <div class="button right" onclick='securityAskDeleteAct("<?PHP echo $act_id;?>")'>
                    Radera
                </div>
                <?PHP
            }
            ?>
            </form>
			<?PHP
			break;
		case "history":
			$sql = mysql_query("SELECT * FROM zc_calendar WHERE date < ". date("Ymd") ." ORDER BY date DESC");
			$activities = new Table(3, "Rubrik|Text|Datum", "325|296|170", "clickActivity");
			while($r = mysql_fetch_assoc($sql))
			{
				$activities->addRow($r['id'], "{$r['title']}|" . cutText($r['text'], 40) . "|{$r['year']}-{$r['month']}-{$r['day']}");
			}
			
			echo "<script src='" . PLUGINFOLDER . "script.js' type='text/javascript'></script>";
			echo "<div class='pagetitle'>Historiska aktiviteter</div>";
			echo "<div class='description'>Här visas alla aktiviteter från och med igår och bakåt i tiden.<br />Klicka på den aktivitet du vill se.</div>";
			?>
            <div class="button right" onclick='javascript: goto(host + "admin/plugin/calendar/activity");' style="margin-bottom:20px;">
                Skapa ny aktivitet
            </div>
            <?PHP
			$activities->printTable();
			break;
		case "save":
			if(isset($_POST['act_id']))
			{
				$id = safeText($_POST['act_id']);					
				$text = safeText($_POST['text']);				
				$title = safeText($_POST['title']);
				$date = safeText($_POST['date']);
				
				if($id == "" || $text == "" || $title == "" || $date == "")
				{
					adminerror("Något av de obligatoriska fälten är tomt!");
				}
				
				$temp = explode("-", $date);
				$day; $month; $year;
				if(count($temp) == 3)
				{
					$year = $temp[0];
					$month = $temp[1];
					$day = $temp[2];
					$datestring = $year . fixZero($month) . fixZero($day);
				}
				else
				{
					adminerror("Fel format på datumet!");
				}
				
				if($id == "Ny aktivitet")
				{
					$sql = mysql_query("INSERT INTO zc_calendar (text, title, year, month, day, date) VALUES ('{$text}', '{$title}', {$year}, {$month}, {$day}, {$datestring})");
					$query = mysql_query("SELECT * FROM zc_calendar WHERE `text` = '{$text}' AND `title` = '{$title}' AND `year` = {$year} AND `month` = {$month} AND `day` = {$day}") or exit(mysql_error());
				}
				else
				{
					$sql = mysql_query("UPDATE zc_calendar SET text = '{$text}', title = '{$title}', year = {$year}, month = {$month}, day = {$day}, date = {$datestring} WHERE id = {$id}");
					$query = mysql_query("SELECT * FROM zc_calendar WHERE id = {$id}");
				}
				$r = mysql_fetch_assoc($query);
				
				go(HOST . "admin/plugin/calendar/activity/" . $r['id'] . "/Sparat!");
			}
			break;
		case "delete":
			if(defined("OPT3"))
			{
				$id = (int)safeText(OPT3);
				$sqlq= mysql_query("SELECT * FROM zc_calendar WHERE id = {$id}");
				$r = mysql_fetch_assoc($sqlq);
				$sql = mysql_query("DELETE FROM zc_calendar WHERE id = {$id}");
			}
			$ret = $_SESSION['return'];
			unset($_SESSION['return']);
			if($ret == HOST . "admin/plugin/calendar/activity/")
			{
				$ret = HOST . "admin/plguin/calendar/";
			}
			go($ret);
			echo $ret;
			break;
		default:
			adminerror(404);
	}
}

function calendar_show()
{
	?>
    <iframe id="calendar_frame" src="<?PHP host(); ?>plugins/calendar/calendar.php" frameBorder="0"></iframe>
    <?PHP
}


function calendar_table_exists($tablename, $database = false)
{

    if(!$database) 
	{
        $res = mysql_query("SELECT DATABASE()");
        $database = mysql_result($res, 0);
    }

    $res = mysql_query("
        SELECT COUNT(*) AS count 
        FROM information_schema.tables 
        WHERE table_schema = '$database' 
        AND table_name = '$tablename'
    ");

    return mysql_result($res, 0) == 1;
}

function fixZero($num)
{
	if((int)$num < 10)
	{
		return "0" . (int)$num;
	}
	else
	{
		return $num;
	}
}
?>