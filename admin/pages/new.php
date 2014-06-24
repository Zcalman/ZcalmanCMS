<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

rightsToSee(1,2);
$newsid = "Ny nyhet";
$title = "Rubrik";
$slug = "";
$text = "";
$oldNews = false;
if(defined("OPT1"))
{
	$s = safeText(OPT1);
	$s = (int)$s;
	$query = mysql_query("SELECT * FROM news WHERE id = {$s} LIMIT 1");
	if($query == false || mysql_num_rows($query) == 0)
	{
		adminerror(404);
	}
	$r = mysql_fetch_assoc($query);
	$newsid = $r['id'];
	$title = $r['title'];
	$slug = $r['slug'];
	$text = $r['text'];
	$oldNews = true;
}


$_SESSION['return'] = $_SESSION['last_page'];
?>
<script type="text/javascript">
var oldSlug = "<?PHP echo $slug; ?>";
var newsid = "<?PHP echo $newsid; ?>";
</script>
<?PHP if(defined("OPT2"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT2) . "</div>";
}
?>
<div class="pagetitle">
Skriv nyhet
</div>
<form action="<?PHP host(); ?>admin/save/news" method="post" id="customerform" name="customerform" onsubmit='javascript: return validateNewsform(this, "Rubrik");'>
<input type="hidden" value="<?PHP echo $slug; ?>" id="slug" name="slug" />
<input type="hidden" value="<?PHP echo $newsid; ?>" id="newsid" name="newsid" />
<input tabindex="1" type="text" class="textfield large-textfield" id="title" name="title" onfocus='javascript:falt(this,this.value,"Rubrik" );' onblur='javascript:tom(this,this.value,"Rubrik");' onchange='javascript: setChanged(this.parent); getNewsSlug(this.value, "<?PHP echo $newsid; ?>");' value="<?PHP echo $title; ?>" />
<div id="pagelink">
<?PHP 
if($oldNews)
{
    echo "<p class=\"sluglink\">Länk: </p> <p id=\"sluglink\" class=\"sluglink\">";
    host(); 
    echo "article/" . $slug . "</p>";
    echo "<a href='#' id='edit_slug_link' onclick='javascript: editNewsSlug();'>Ändra</a>";
}
?>
</div>
<?PHP 
if($oldNews)
{	
	?>
    <div class="button right" style="margin-bottom:20px;" onclick='javascript: gotonew("<?PHP host(); echo "/article/" . $slug; ?>")'>Öppna artiklen i nytt fönster</div>
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
    <div class="button right" onclick='securityAskDeleteNews("<?PHP echo $title . '","' . $newsid;?>")'>
        Radera
    </div>
    <?PHP
}
?>
</form>