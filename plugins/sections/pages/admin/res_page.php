<?PHP
global $host;
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
$pageid = "Ny resultatsida";
$title = "Rubrik";
$slug = "";
$text = "";
$iframelink = "URL till sidan som ska visas";
$startx = "0";
$starty = "0";
$oldPage = false;
$verb = "Skapa";
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
	$query = mysql_query("SELECT * FROM sections_pages WHERE id = {$s} AND extrawide = 1 LIMIT 1");
	if($query == false || mysql_num_rows($query) == 0)
	{
		error(404);
	}
	$r = mysql_fetch_assoc($query);
	$pageid = $r['id'];
	$title = $r['title'];
	$slug = $r['slug'];
	$text = $r['text'];
	$oldPage = true;
}

$RTE = "show";
$iframe = "hide";
if($oldPage)
{
	$verb = "Editera";
}
$_SESSION['return'] = $_SESSION['last_page'];
?>
<script type="text/javascript">
var oldSlug = "<?PHP echo $slug; ?>";
var pageid = "<?PHP echo $pageid; ?>";
$(document).ready(function() {
	$("#RTEditorBox").<?PHP echo $RTE; ?>();
	$("#iFrameBox").<?PHP echo $iframe; ?>();
});
</script>
<?PHP if(defined("OPT5"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT5) . "</div>";
}
?>
<div class="pagetitle">
<?PHP echo $verb; ?> en resultatsida i sektionen <?PHP echo sections_getSectionName($section_id); ?>
</div>
<form action="<?PHP host(); ?>admin/plugin/sections/save/respage" method="post" name="customerform" id="customerform" onsubmit='javascript: return validatePageform(this, "Rubrik", "URL till sidan som ska visas");'>
<input type="hidden" value="<?PHP echo $slug; ?>" id="slug" name="slug" />
<input type="hidden" id="pageid" name="pageid" value="<?PHP echo $pageid; ?>" />
<input type="hidden" id="sectionid" name="sectionid" value="<?PHP echo $section_id; ?>" />
<input tabindex="1" type="text" class="textfield large-textfield" id="title" name="title" onfocus='javascript:falt(this,this.value,"Rubrik" );' onblur='javascript:tom(this,this.value,"Rubrik");' onchange='javascript: setChanged(customerform); <?PHP if($slug != "start") { ?>getSectionPageSlug(this.value, "<?PHP echo $pageid; ?>");<?PHP } ?>' value="<?PHP echo $title; ?>" />
<div id="pagelink">
<?PHP 
if($oldPage)
{
    echo "Länk: ";
    host(); 
    echo sections_getSectionSlug($section_id) . "/" . $slug;
	if($slug != "start")
	{
    	echo "<a href='#' id='edit_slug_link' onclick='javascript: editPageSlug();'>Ändra</a>";
	}
}
?>
</div>
<div class="button right" style="margin-bottom:20px;" onclick='javascript: gotonew("<?PHP host(); echo sections_getSectionSlug($section_id) . "/" . $slug; ?>")'>Öppna resultatsidan i nytt fönster</div>
<div id="RTEditorBox">
<?PHP
include("RTEditor.php");
?>
</div>
<div id="iFrameBox">
    <input tabindex="3" type="text" class="textfield" id="iframelink" name="iframelink" onfocus='javascript:falt(this,this.value,"URL till sidan som ska visas" );' onblur='javascript:tom(this,this.value,"URL till sidan som ska visas");' onchange="javascript: setChanged(customerform);" style="width:80%;" value="<?PHP echo $iframelink; ?>" />
    <table class="settingstable">
    	<tr valign="top">
        	<td class="settingstitle">
            	Start X
            </td>
        	<td class="settingsfield">
            	<input tabindex="4" type="text" class="textfield" id="startx" name="startx" onchange='javascript: setChanged(customerform);' value="<?PHP echo $startx; ?>" />
            </td>
        </tr>
    	<tr valign="top">
        	<td class="settingstitle">
            	Start Y
            </td>
        	<td class="settingsfield">
            	<input tabindex="5" type="text" class="textfield" id="starty" name="starty" onchange='javascript: setChanged(customerform);' value="<?PHP echo $starty; ?>" />
            </td>
        </tr>
    </table>
</div>
<br />
<div class="customerfalt" style="float:left;">
	<input tabindex="6" type="submit" value="Spara" class="submitbutton" onclick="javascript: if(validatePageform(customerform)) { customerform.submit();}"/>
</div>
<?PHP if($oldPage && $slug != "start")
{
	?>
    <div class="button right" onclick='securityAskDeleteSectionPage("<?PHP echo $title . '","' . $pageid;?>")'>
        Radera
    </div>
    <?PHP
}
?>
<div class="button right" onclick='javascript: goto("<?PHP host(); ?>admin/plugin/sections/sectionrespages/<?PHP echo $section_id; ?>")'>
    Tillbaka
</div>
</form>
<div class="hidden">
    <div id="edit_slug_dialog" title="Ändra länk">
        <p>När du trycker på ändra uppdateras bara länken tillfälligt. Kontrollera sedan att länken blir som du vill. När du är klar med dina ändringar, tryck spara.</p>
        <form name="edit_slug_form" id="edit_slug_form" action="#" onsubmit='javascript: getSectionPageSlug(this.slugtext.value, "<?PHP echo $pageid; ?>"); $("#edit_slug_dialog").dialog("close"); return false;' method="post">
        <br /><strong>Länk</strong><br />
            <?PHP host(); echo sections_getSectionSlug($section_id) . "/";?><input type="text" name="slugtext" id="slugtext" class="textfield" />
        </form>
    </div>
    <div id="delete_page_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera resultatsidan <span style="font-weight:bold;" id="delete_pagetitle"></span>?</div>
</div>