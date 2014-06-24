<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
$pageid = "Ny sida";
$title = "Rubrik";
$slug = "";
$text = "";
$type = "html";
$iframelink = "URL till sidan som ska visas";
$startx = "0";
$starty = "0";
$oldPage = false;
$verb = "Skapa";
$form = 0;
if(defined("OPT1"))
{
	$s = safeText(OPT1);
	$s = (int)$s;
	$query = mysql_query("SELECT * FROM pages WHERE id = {$s} LIMIT 1");
	if($query == false || mysql_num_rows($query) == 0)
	{
		error(404);
	}
	$r = mysql_fetch_assoc($query);
	$pageid = $r['id'];
	$title = $r['title'];
	$slug = $r['slug'];
	$text = $r['text'];
	$iframelink = $r['link'];
	$type = $r['type'];
	$startx = $r['startx'];
	$starty = $r['starty'];
	$form = $r['form'];
	$oldPage = true;
}

$RTE = "show";
$iframe = "hide";
if($oldPage)
{
	$verb = "Editera";
	if($type == "html")
	{
		$RTE = "show";
		$iframe = "hide";
	}
	else
	{
		$RTE = "hide";
		$iframe = "show";
	}
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
<?PHP if(defined("OPT2"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT2) . "</div>";
}
?>
<div class="pagetitle">
<?PHP echo $verb; ?> en sida
</div>
<form action="<?PHP host(); ?>admin/save/page" method="post" name="customerform" id="customerform" onsubmit='javascript: return validatePageform(this, "Rubrik", "URL till sidan som ska visas");'>
<input type="hidden" value="<?PHP echo $slug; ?>" id="slug" name="slug" />
<input type="hidden" id="pageid" name="pageid" value="<?PHP echo $pageid; ?>" />
<input tabindex="1" type="text" class="textfield large-textfield" id="title" name="title" onfocus='javascript:falt(this,this.value,"Rubrik" );' onblur='javascript:tom(this,this.value,"Rubrik");' onchange='javascript: setChanged(customerform); getPageSlug(this.value, "<?PHP echo $pageid; ?>");' value="<?PHP echo $title; ?>" />
<div id="pagelink">
<?PHP 
if($oldPage)
{
    echo "<p class=\"sluglink\">Länk: </p> <p id=\"sluglink\" class=\"sluglink\">";
    host(); 
    echo $slug . "</p>";
    echo "<a href='#' id='edit_slug_link' onclick='javascript: editSlug();'>Ändra</a>";
}
?>
</div>
<div class="dropbox" style="margin-bottom:20px;">
    <select name="type" id="type" tabindex="2" onchange="javascript: setChanged(customerform); setPageType(this.value);" class="dropdown">
        <option <?PHP setSel($type, "html"); ?>value="html">Standard HTML-sida</option>
        <option <?PHP setSel($type, "iframe"); ?>value="iframe">iFrame-sida</option>
    </select>
</div>
<?PHP 
if($oldPage)
{	
	?>
    <div class="button right" onclick='javascript: gotonew("<?PHP host(); echo $slug; ?>")'>Öppna sidan i nytt fönster</div>
    <?PHP
}
?>
<div id="RTEditorBox">
<?PHP
include("RTEditor.php");
?>

<div class="description" style="float:left; width:100%; margin-top:20px; margin-bottom:20px;;">
	<h2>Formulär</h2>
    Välj om sidan ska visa ett formulär längst ner på sidan.<br />

    <div class="dropbox" style="margin-top:10px;">
        <select name="formid" id="formid" tabindex="5" onchange="javascript: setChanged(customerform);" class="dropdown">
        	<option <?PHP setSel($form, "0"); ?>value="0">Inget formulär</option>
            <?PHP
			$formQuery = mysql_query("SELECT * FROM `forms`");
			while($f = mysql_fetch_assoc($formQuery)) {
				$settings = explode("|", $f['settings']);
				for($i = 0; $i < count($settings); $i++) {
					$settings[$i] = explode(":", $settings[$i], 2);
					$settings[$settings[$i][0]] = $settings[$i][1];
					unset($settings[$i]);
				}
				?>
                <option <?PHP setSel($form, $f['id']); ?>value="<?PHP echo $f['id']; ?>"><?PHP echo $f['id'] . " - " . $settings['title']; ?></option>
                <?PHP
			}
			?>
        </select>
    </div>
</div>

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
<?PHP if($oldPage)
{
	?>
    <div class="button right" onclick='securityAskDeletePage("<?PHP echo $title . '","' . $pageid;?>")'>
        Radera
    </div>
    <?PHP
}
?>
</form>