<?PHP
// Awesome code goes here
rightsToSee(1);
?>
<div class="pagetitle">
Uppdateringslogg
</div>
<?PHP
if(file_exists("../" . $upd_log_file))
{
	?>
    <div class="description" style="height:700px; overflow:auto; border:1px solid #333; width:700px; padding:5px; color:#666;">
	<?PHP
    $ulf = fopen("../" . $upd_log_file, "r");
	while(!feof($ulf))
	{
		echo fgets($ulf) . "<br />";
	}
	fclose($ulf);
	?>
	</div>
    <?PHP
}
else
{
	?>
    <div class="description">
    	<p class="smalltext italic" style="color:#999;">Finns ingen logg att visa!</p>
    </div>
    <?PHP
}
?>