<?PHP
// Awesome code goes here
rightsToSee(1);
?>
<script src="<?PHP adminhost(); ?>updatescript/update.js" type="text/javascript"></script>
<?PHP
if($_SESSION['need_update'])
{
	?>
    <div class="pagetitle">
    Uppdatera Zimba CMS
    </div>
    <div class="description">
    	Det finns en nyare version av Zimba CMS!<br />
        Vi rekommenderar att du uppdaterar till den nyare versionen!<br />
        Genom att hålla Zimba CMS uppdaterat behåller du säkerheten och stabiliteten i systemet.<br /><br />
        <input type="button" id="run_update_button" class="submitbutton" value="Uppdatera!" />
        <div id="update_box">
        	<span class="italic smalltext" style="margin-left:10px; color:#666;">Uppdaterar Zimba CMS...</span><br />
            <img src="<?PHP adminhost(); ?>images/bar-loader.gif" /><br /><br />
        	<strong>VIKTIGT!<br />
            Avbryt aldrig en pågående uppdatering!</strong><br />
            Byt inte sida, klicka inte på någon länk eller liknande innan uppdateringen är klar!<br />
        </div>
    </div>
    <?PHP
}
else
{
	?>
    <div class="pagetitle">
    Zimba CMS är uppdaterad
    </div>
    <div class="description">
    	Du har den senaste versionen av Zimba CMS!<br />
        Genom att hålla Zimba CMS uppdaterat behåller du säkerheten och stabiliteten i systemet.<br /><br />
        Om du av någon anledning vill göra om den senaste uppdateringen, t.ex. om <br />
        den misslyckades senast du körde den, klicka på knappen nedan.<br /><br />
        <div class="button" id="force_update_button">Tvinga ominstallation av senaste uppdatering!</div>
        <div id="update_box">
        	<span class="italic smalltext" style="margin-left:10px; color:#666;">Gör om senaste uppdatering...</span><br />
            <img src="<?PHP adminhost(); ?>images/bar-loader.gif" /><br /><br />
        	<strong>VIKTIGT!<br />
            Avbryt aldrig en pågående uppdatering!</strong><br />
            Byt inte sida, klicka inte på någon länk eller liknande innan uppdateringen är klar!<br />
        </div>
    </div>
    <?PHP
}
?>