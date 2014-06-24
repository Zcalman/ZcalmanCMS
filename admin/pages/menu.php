<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

rightsToSee(1);
$menuid = "Ny länk";
$title = "";
$link = "";
$parent = "";
$target = "_self";
$oldLink = false;
$submenu = 0;
$menutitle = "Nytt menyval";
$active = 1;
if(defined("OPT1"))
{
	$s = safeText(OPT1);
	$s = (int)$s;
	$query = mysql_query("SELECT * FROM menu WHERE id = {$s} LIMIT 1");
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
if(defined("OPT2"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT2) . "</div>";
}
?>
<div class="pagetitle">
<?PHP echo $menutitle; ?>
</div>
<form action="<?PHP host(); ?>admin/save/menu" method="post" name="customerform" id="customerform" onsubmit="javascript: return validateMenu(this);">
<input type="hidden" id="menuid" name="menuid" value="<?PHP echo $menuid; ?>" />
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
$ask = "securityAskDeleteSubMenu";
$_SESSION['return'] = HOST . "admin/menu/" . $parent;
if($parent == 0)
{
	$ask = "securityAskDeleteMenu";
	$_SESSION['return'] = HOST . "admin/menus";
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
        <ul id="mainmenu_sort">
        <?PHP
        // Tar fram listan från Databasen
        $result = mysql_query("SELECT * FROM menu WHERE parent = {$menuid} ORDER BY `sort` ASC") or die(mysql_error());
        while($row = mysql_fetch_array($result)) 
        {
            echo "<li id='menu_" . $row['id'] . "'>{$row['title']}";
			if($row['active'] == 0)
			{
				echo " <span class='italic table-description'>(Ej aktiv)</span>";
			}
			echo "<img src='". HOST . "admin/images/icons/pencil.png' alt='Editera meny' onclick='javascript: goto(\"". HOST . "admin/menu/". $row['id'] ."\");' style='border:0px;' /></li>";
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