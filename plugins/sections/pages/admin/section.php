<?PHP
global $host;
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1);
$pageid = "Ny sektion";
$name = "Namn";
$slug = "";
$oldPage = false;
$verb = "Skapa";
if(defined("OPT3"))
{
	$s = safeText(OPT3);
	$s = (int)$s;
	$query = mysql_query("SELECT * FROM sections WHERE id = {$s} LIMIT 1");
	if($query == false || mysql_num_rows($query) == 0)
	{
		error(404);
	}
	$r = mysql_fetch_assoc($query);
	$pageid = $r['id'];
	$name = $r['name'];
	$slug = $r['slug'];
	$oldPage = true;
	$verb = "Editera";
}

$_SESSION['return'] = $_SESSION['last_page'];
?>
<script type="text/javascript">
var oldSlug = "<?PHP echo $slug; ?>";
var pageid = "<?PHP echo $pageid; ?>";
</script>
<?PHP if(defined("OPT4"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT4) . "</div>";
}
?>
<div class="pagetitle">
<?PHP echo $verb; ?> en sektion
</div>
<form action="<?PHP host(); ?>admin/plugin/sections/save/section" method="post" name="customerform" id="customerform" onsubmit='javascript: return validatePageform(this, "Rubrik", "URL till sidan som ska visas");'>
<input type="hidden" value="<?PHP echo $slug; ?>" id="slug" name="slug" />
<input type="hidden" id="pageid" name="pageid" value="<?PHP echo $pageid; ?>" />
<input tabindex="1" type="text" class="textfield large-textfield" id="name" name="name" onfocus='javascript:falt(this,this.value,"Namn" );' onblur='javascript:tom(this,this.value,"Namn");' onchange='javascript: setChanged(customerform); getSectionSlug(this.value, "<?PHP echo $pageid; ?>");' value="<?PHP echo $name; ?>" />
<div id="pagelink">
<?PHP 
if($oldPage)
{
    echo "Länk: ";
    host(); 
    echo $slug;
    echo "<a href='#' id='edit_slug_link' onclick='javascript: editSectionSlug();'>Ändra</a>";
}
?>
</div>
<br />
<div class="customerfalt" style="float:left;">
	<input tabindex="6" type="submit" value="Spara" class="submitbutton" onclick="javascript: if(validateSectionform(customerform)) { customerform.submit();}"/>
</div>
<?PHP if($oldPage)
{
	?>
    <div class="button right" onclick='securityAskDeleteSection("<?PHP echo $name . '","' . $pageid;?>")'>
        Radera
    </div>
            	<div class="button right" onclick='goto("<?PHP adminhost(); ?>plugin/sections/")'>Tillbaka</div>
    <?PHP
}
?>
</form>
<div class="hidden">
    <div id="edit_slug_dialog" title="Ändra länk">
        <p>När du trycker på ändra uppdateras bara länken tillfälligt. Kontrollera sedan att länken blir som du vill. När du är klar med dina ändringar, tryck spara.</p>
        <form name="edit_slug_form" id="edit_slug_form" action="#" onsubmit='javascript: getSectionSlug(this.slugtext.value, "<?PHP echo $pageid; ?>"); $("#edit_slug_dialog").dialog("close"); return false;' method="post">
        <br /><strong>Länk</strong><br />
            <?PHP host(); ?><input type="text" name="slugtext" id="slugtext" class="textfield" />
        </form>
    </div> 
    <div id="delete_section_dialog" title="Säkerhetsfråga"><strong>SÄKERHETSFRÅGA</strong><br /><br />Är du helt säker på att du vill radera sektionen <span style="font-weight:bold;" id="delete_sectionname"></span>?<br /><br />Allt som har med sektionen att göra kommer att raderas!</div> 
</div>