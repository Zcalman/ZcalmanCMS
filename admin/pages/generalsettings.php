<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
$use_mobile_theme = get_setting("use_mobile_theme");
rightsToSee(1);
if(defined("OPT1"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT1) . "</div>";
}
?>
<div class="pagetitle">
	Allmänna inställningar
</div>
<form action="<?PHP adminhost(); ?>save/settings" method="post" name="customerform" id="customerform">
<input type="hidden" id="changedfields" name="changedfields" value="" />
<table class="settingstable">
    <tr valign="top">
        <td class="settingstitle">
            Namn på hemsidan
        </td>
        <td class="settingsfield">
            <input tabindex="1" type="text" class="textfield" id="pagetitle" name="pagetitle" onchange='javascript: setSettingsChanged(customerform, "pagetitle");' value="<?PHP echo PAGE_TITLE; ?>" />
        </td>
    </tr>
    <tr valign="top">
        <td class="settingstitle">
            Beskrivning
        </td>
        <td class="settingsfield">
            <input tabindex="2" type="text" class="textfield" id="pagedescription" name="pagedescription" onchange='javascript: setSettingsChanged(customerform, "pagedescription");' value="<?PHP echo PAGE_DESCRIPTION; ?>" />
        </td>
    </tr>
    <tr valign="top">
        <td class="settingstitle">
            Använd mobilanpassat tema <span class="italic table-description">(Om ditt aktuella tema stödjer det)</span>
        </td>
        <td class="settingsfield">
            <div class="dropbox">
                <select tabindex="3" name="use_mobile_theme" id="use_mobile_theme" class="dropdown" onchange='javascript: setSettingsChanged(customerform, "use_mobile_theme");'>
                    <option <?PHP setSel($use_mobile_theme, "true"); ?>value="true">Ja</option>
                    <option <?PHP setSel($use_mobile_theme, "false"); ?>value="false">Nej</option>
                </select>
            </div>
        </td>
    </tr>
    <tr valign="top">
        <td class="settingstitle">
            Text till mobila användare<br /><span class="italic table-description">(Första gången de ser det anpassade temat)</span>
        </td>
        <td class="settingsfield">
             <textarea tabindex="4" class="textfield" style="width:300px; height:80px;" id="portable_device_text" name="portable_device_text" onchange='javascript: setSettingsChanged(customerform, "portable_device_text");'><?PHP echo get_setting('portable_device_text'); ?></textarea>
        </td>
    </tr>
</table>
<input type="submit" class="submitbutton" value="Spara" />
</form>