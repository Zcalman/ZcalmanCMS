<?PHP
global $host;
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

rightsToSee(1,2);
$newsid = "Ny nyhet";
$title = "Rubrik";
$slug = "";
$text = "";
$verb = "Skapa";
$oldNews = false;
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
	$query = mysql_query("SELECT * FROM sections_news WHERE id = {$s} LIMIT 1");
	if($query == false || mysql_num_rows($query) == 0)
	{
		adminerror(404);
	}
	$r = mysql_fetch_assoc($query);
	$newsid = $r['id'];
	$title = $r['title'];
	$slug = $r['slug'];
	$text = $r['text'];
	$verb = "Editera";
	$oldNews = true;
}


$_SESSION['return'] = $_SESSION['last_page'];
?>
<script type="text/javascript">
var oldSlug = "<?PHP echo $slug; ?>";
var newsid = "<?PHP echo $newsid; ?>";
</script>
<?PHP if(defined("OPT5"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT5) . "</div>";
}
?>
<div class="pagetitle">
<?PHP echo $verb; ?> en nyhet i sektionen <?PHP echo sections_getSectionName($section_id); ?>
</div>
<form action="<?PHP host(); ?>admin/plugin/sections/save/news" method="post" id="customerform" name="customerform" onsubmit='javascript: return validateNewsform(this, "Rubrik");'>
<input type="hidden" value="<?PHP echo $slug; ?>" id="slug" name="slug" />
<input type="hidden" value="<?PHP echo $newsid; ?>" id="newsid" name="newsid" />
<input type="hidden" id="sectionid" name="sectionid" value="<?PHP echo $section_id; ?>" />
<input tabindex="1" type="text" class="textfield large-textfield" id="title" name="title" onfocus='javascript:falt(this,this.value,"Rubrik" );' onblur='javascript:tom(this,this.value,"Rubrik");' onchange='javascript: setChanged(this.parent); getSectionNewsSlug(this.value, "<?PHP echo $newsid; ?>");' value="<?PHP echo $title; ?>" />
<div id="pagelink">
<?PHP 
if($oldNews)
{
    echo "<p class=\"sluglink\">Länk: </p> <p id=\"sluglink\" class=\"sluglink\">";
    host(); 
    echo sections_getSectionSlug($section_id) . "/article/" . $slug . "</p>";
    echo "<a href='#' id='edit_slug_link' onclick='javascript: editNewsSlug();'>Ändra</a>";
}
?>
</div>
<?PHP 
if($oldNews)
{	
	?>
    <div class="button right" style="margin-bottom:20px;" onclick='javascript: gotonew("<?PHP host(); echo sections_getSectionSlug($section_id) . "/article/" . $slug; ?>")'>Öppna artiklen i nytt fönster</div>
    <?PHP
}
?>
<div id="RTEditorBox">
<?PHP
include("RTEditor.php");
?>
</div>
<br />
<div class="customerfalt" style="float:left;">
	<input tabindex="6" type="button" value="Spara" class="submitbutton" style="cursor:pointer;" onclick="javascript: if(validateNewsform(customerform)) { customerform.submit();}"/>
</div>
<?PHP if($oldNews)
{
	?>
    <div class="button right" onclick='securityAskDeleteSectionNews("<?PHP echo $title . '","' . $newsid;?>")'>
        Radera
    </div>
    <?PHP
}
?>
<div class="button right" onclick='javascript: goto("<?PHP host(); ?>admin/plugin/sections/sectionnews/<?PHP echo $section_id; ?>")'>
    Tillbaka
</div>
</form>
<div class="hidden">
    <div id="edit_slug_dialog" title="Ändra länk">
        <p>När du trycker på ändra uppdateras bara länken tillfälligt. Kontrollera sedan att länken blir som du vill. När du är klar med dina ändringar, tryck spara.</p>
        <form name="edit_slug_form" id="edit_slug_form" action="#" onsubmit='javascript: getSectionNewsSlug(this.slugtext.value, "<?PHP echo $newsid; ?>"); $("#edit_slug_dialog").dialog("close"); return false;' method="post">
        <br /><strong>Länk</strong><br />
            <?PHP host(); echo sections_getSectionSlug($section_id) . "/article/";?><input type="text" name="slugtext" id="slugtext" class="textfield" />
        </form>
    </div> 
    <div id="delete_news_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera nyheten <span style="font-weight:bold;" id="delete_newstitle"></span>?</div>
</div>