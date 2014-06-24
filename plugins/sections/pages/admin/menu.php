<?PHP
global $host;
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

rightsToSee(1,2);
$menuid = "Ny länk";
$title = "";
$link = "";
$parent = "";
$target = "_self";
$oldLink = false;
$submenu = 0;
$menutitle = "Nytt menyval";
$active = 1;
if(defined("OPT3"))
{ 
	$section_id = mysql_real_escape_string(trim(OPT3)); 
	if(!is_admin())
	{
		if(!in_array($section_id, $_SESSION['sections_rights']))
		{
			adminerror(403);
		}
	}
} 
else
{
	adminerror(404);	
}
if(defined("OPT4"))
{
	$s = safeText(OPT4);
	$s = (int)$s;
	$query = mysql_query("SELECT * FROM sections_menu WHERE id = {$s} LIMIT 1");
	if($query == false || mysql_num_rows($query) == 0)
	{
		error(404);
	}
	$r = mysql_fetch_assoc($query);
	$menuid = $r['id'];
	$title = $r['title'];
	$link = $r['link'];
	$parent = $r['parent'];
	$target = $r['target'];
	$oldLink = true;
	$submenu = $r['submenu'];
	$active = $r['active'];
	$menutitle = "Editera menyval";
}

if($submenu == 1)
{
	echo "<script type=\"text/javascript\">$(document).ready(function() { $('#submenufield').show();});</script>";
}
?>

<?PHP 
if(defined("OPT5"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT5) . "</div>";
}
?>
<link href="<?PHP host(); ?>plugins/sections/pages/admin/style.css" rel="stylesheet" type="text/css" />
<div class="pagetitle">
<?PHP echo $menutitle; ?> i sektionen <?PHP echo sections_getSectionName($section_id); ?>
</div>
<form action="<?PHP host(); ?>admin/plugin/sections/save/menu" method="post" name="customerform" id="customerform" onsubmit="javascript: return validateMenu(this);">
<input type="hidden" id="menuid" name="menuid" value="<?PHP echo $menuid; ?>" />
<input type="hidden" id="sectionid" name="sectionid" value="<?PHP echo $section_id; ?>" />
<table class="settingstable">
    <tr>
        <td class="settingstitle">
            Text
        </td>
        <td class="settingsfield">
    		<input tabindex="1" type="text" class="textfield" id="title" name="title" onchange='javascript: setChanged(customerform);' value="<?PHP echo $title; ?>" />
        </td>
    </tr>
    <tr>
        <td class="settingstitle">
            Länk <span class="italic table-description">(Inklusive http://)</span>
        </td>
        <td class="settingsfield">
    		<input tabindex="2" type="text" class="textfield" id="menulink" name="menulink" onchange='javascript: setChanged(customerform);' style="width:300px;" value="<?PHP echo $link; ?>" />
        </td>
    </tr>
    <tr>
        <td class="settingstitle">
            Öppna länken i
        </td>
        <td class="settingsfield">
            <div class="dropbox">
                <select name="target" tabindex="3" id="target" onchange="javascript: setChanged(customerform);" class="dropdown">
                    <option <?PHP setSel($target, "_self"); ?>value="_self">Samma fönster</option>
                    <option <?PHP setSel($target, "_blank"); ?>value="_blank">Nytt fönster</option>
                </select>
            </div>
        </td>
    </tr>
    <?PHP if($oldLink && $parent == 0 || !$oldLink)
	{
	?>
    <tr>
        <td class="settingstitle">
            Undermeny
        </td>
        <td class="settingsfield">
            <div class="dropbox">
                <select name="submenu" tabindex="4" id="submenu" onchange="javascript: setChanged(customerform);" class="dropdown">
                    <option <?PHP setSel($submenu, 0); ?>value="0">Nej</option>
                    <option <?PHP setSel($submenu, 1); ?>value="1">Ja</option>
                </select>
            </div>
        </td>
    </tr>
    <?PHP
	}
	else
	{
		?>
        <input type="hidden" id="submenu" name="submenu" value="<?PHP echo $submenu; ?>" />
        <?PHP
	}
	?>
    <tr>
        <td class="settingstitle">
            Aktiv
        </td>
        <td class="settingsfield">
            <div class="dropbox">
                <select name="active" tabindex="5" id="active" onchange="javascript: setChanged(customerform);" class="dropdown">
                    <option <?PHP setSel($active, "1"); ?>value="1">Ja</option>
                    <option <?PHP setSel($active, "0"); ?>value="0">Nej</option>
                </select>
            </div>
        </td>
    </tr>
</table>
<input tabindex="4" type="submit" value="Spara" class="submitbutton" style="cursor:pointer;" />
<?PHP 
$ask = "securityAskDeleteSectionsSubMenu";
$_SESSION['return'] = HOST . "admin/plugin/sections/menu/". $section_id . "/" . $parent;
if($parent == 0)
{
	$ask = "securityAskDeleteSectionsMenu";
	$_SESSION['return'] = HOST . "admin/plugin/sections/menus/". $section_id . "";
}
if($oldLink)
{
	?>
    <div class="button right" onclick='<?PHP echo $ask; ?>("<?PHP echo $title . '","' . $menuid;?>")'>
        Radera
    </div>
    <div class="button right" onclick='javascript: goto("<?PHP echo $_SESSION['return']; ?>");'>
        Tillbaka
    </div>
    <?PHP
}
?>
</form>
<div id="submenufield">
    <div class="pagetitle">
    Undermenyhantering
    </div>
<?PHP 
if($parent == 0 && $oldLink)
{
	?>
	<div class="description">
	Dra de olika menylänkarna i den ordning du vill att de ska synas på hemsidan.<br />
	För att ändra själva undermenyslänken, klicka på pennan.</div>
    
    <div style="float:left; width:300px;">
        <ul id="sectionmenu_sort">
        <?PHP
        // Tar fram listan från Databasen
        $result = mysql_query("SELECT * FROM sections_menu WHERE parent = {$menuid} ORDER BY `sort` ASC") or die(mysql_error());
        while($row = mysql_fetch_array($result)) 
        {
            echo "<li id='menu_" . $row['id'] . "'>{$row['title']}";
			if($row['active'] == 0)
			{
				echo " <span class='italic table-description'>(Ej aktiv)</span>";
			}
			echo "<img src='". HOST . "admin/images/icons/pencil.png' alt='Editera meny' onclick='javascript: goto(\"". HOST . "admin/plugin/sections/menu/". $section_id . "/". $row['id'] ."\");' style='border:0px;' /></li>";
        }
        ?>
        </ul>
        <div id="new_submenulink" class="button nomargin">
            Ny undermenyslänk
        </div>
    </div>
	<?PHP
}
?>
</div>

<div class="hidden">
    <div id="delete_sectionsmenu_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera menylänken <span style="font-weight:bold;" id="delete_sectionsmenutitle"></span>?<br /><br />Alla tillhörande undermenylänkar kommer också försvinna.</div>
    <div id="delete_sectionssubmenu_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera undermenylänken <span style="font-weight:bold;" id="delete_sectionssubmenutitle"></span>?</div>
    <div id="new_sectionssubmenulink_dialog" title="Ny undermenyslänk">
    	<form name="new_submenulink_form" id="new_submenulinkform" action="<?PHP host(); ?>admin/plugin/sections/save/submenu" onsubmit="javascript: return validateSubMenu(this);" method="post">
        <input type="hidden" name="parent" id="parent" value="<?PHP echo $menuid; ?>" />
		<input type="hidden" id="sectionidsub" name="sectionidsub" value="<?PHP echo $section_id; ?>" />
        <table class="settingstable">
            <tr>
                <td class="settingstitle">
                    Rubrik
                </td>
                <td class="settingsfield">
        			<input type="text" name="subtitle" id="subtitle" class="textfield" />
                </td>
            </tr>
            <tr>
                <td class="settingstitle">
                    Länk <span class="italic table-description">(Inklusive http://)</span>
                </td>
                <td class="settingsfield">
        			<input type="text" name="submenulink" id="submenulink" class="textfield" />
                </td>
            </tr>
            <tr>
                <td class="settingstitle">
                    Öppna länken i
                </td>
                <td class="settingsfield">
                	<div class="dropbox">
                        <select name="submenutarget" class="dropdown" id="submenutarget">
                            <option <?PHP setSel($target, "_self"); ?>value="_self">Samma fönster</option>
                            <option <?PHP setSel($target, "_blank"); ?>value="_blank">Nytt fönster</option>
                        </select>
                    </div>
                </td>
            </tr>
        </table>
        </form>
    </div>
</div>