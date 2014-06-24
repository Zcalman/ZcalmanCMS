<?PHP
$default_section = get_setting("default_section");
$use_sectionstart_default = get_setting("use_sectionstart_default");
rightsToSee(1);
if(defined("OPT3"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT3) . "</div>";
}
?>
<div class="pagetitle">
	Sektioner - Inställningar
</div>
<form action="<?PHP adminhost(); ?>plugin/sections/save/settings" method="post" name="customerform" id="customerform">
<input type="hidden" id="changedfields" name="changedfields" value="" />
<table class="settingstable">
    <tr valign="top">
        <td class="settingstitle">
            Standard sektion <span class="italic table-description">(Den sektion som används på startsidan)</span>
        </td>
        <td class="settingsfield">
            <div class="dropbox">
                <select tabindex="3" name="default_section" id="default_section" class="dropdown" onchange='javascript: setSettingsChanged(customerform, "default_section");'>
                    <option <?PHP setSel($default_section, "No such setting in database"); ?>value="No such setting in database">Ingen vald</option>
                    <?PHP
                    $sql = mysql_query("SELECT * FROM sections");
                    while($r = mysql_fetch_assoc($sql))
                    {
						?>
                        <option <?PHP setSel($default_section, $r['id']); ?>value="<?PHP echo $r['id']; ?>"><?PHP echo $r['name']; ?></option>
                        <?PHP
                    }
                    ?>
                </select>
            </div>
        </td>
    </tr>
    <tr valign="top">
        <td class="settingstitle">
            Använd standard sektionens startsida som huvudstartsida <span class="italic table-description"></span>
        </td>
        <td class="settingsfield">
            <div class="dropbox">
                <select tabindex="3" name="use_sectionstart_default" id="use_sectionstart_default" class="dropdown" onchange='javascript: setSettingsChanged(customerform, "use_sectionstart_default");'>
                    <option <?PHP setSel($use_sectionstart_default, "true"); ?>value="true">Ja</option>
                    <option <?PHP setSel($use_sectionstart_default, "false"); ?>value="false">Nej</option>
                </select>
            </div>
        </td>
    </tr>
</table>
<input type="submit" class="submitbutton" value="Spara" />
</form>