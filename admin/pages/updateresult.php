<?PHP
// Awesome code goes here
rightsToSee(1);

if(defined("OPT1"))
{
	if(OPT1 == "succed")
	{
		set_setting("last_succeeded_version", $cms_version);
		?>
        <div class="pagetitle">
        	<span style="color:#393;">Uppdateringen lyckades!</span>
        </div>
        <div class="description">
        	Uppdateringen av Zimba CMS genomfördes felfritt!<br />
            Systemet är nu uppdaterat och fungerar precis som vanligt. <br /><br />
        </div>
        <?PHP
	}
	elseif(OPT1 == "errors" && defined("OPT2"))
	{
		?>
        <div class="pagetitle">
        	<span style="color:#F03;">Uppdateringen misslyckades!</span>
        </div>
        <div class="description">
        	Uppdateringen av Zimba CMS genomfördes men stötte på <span class="italic" style="color:#F03;"><?PHP echo safeText(OPT2); ?></span> fel!<br />
            Beroende på vad som gick fel kan systemet fungera iallafall.<br />
           	Försök göra om uppdateringen <a href="<?PHP adminhost(); ?>update">här</a> och om inte det hjälper kontakta <a href="mailto:niclas@zcalman.se">niclas@zcalman.se</a><br /><br />
            För att se vad som gått fel, ta en titt i <a href="<?PHP adminhost(); ?>updatelog">Uppdateringsloggen</a>.<br />
        </div>
        <?PHP
	}
	else
	{
		adminerror(404);
	}
}
else
{
	adminerror(404);
}
?>