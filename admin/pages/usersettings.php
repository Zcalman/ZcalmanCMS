<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
rightsToSee(1,2);
$s = safeText($_SESSION['userid']);
$s = (int)$s;
$query = mysql_query("SELECT * FROM userbase WHERE id = {$s} LIMIT 1");
if($query == false || mysql_num_rows($query) == 0)
{
	error(404);
}
$r = mysql_fetch_assoc($query);
$userid = $r['id'];
$useremail = $r['email'];
$oldUser = true;
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#customerinfo').hide();
});
</script>
<?PHP if(defined("OPT2"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT2) . "</div>";
}
?>
<div class="pagetitle">
Mitt konto
</div>
<form action="<?PHP host(); ?>admin/save/my-settings" method="post" id="customerform">
<table class="settingstable">
    <tr>
        <td class="settingstitle">
            E-postadress
        </td>
        <td class="settingsfield">
    		<input tabindex="1" type="text" class="textfield" name="email" id="email" onchange="javascript: setChanged(customerform);" value="<?PHP echo $useremail; ?>" />
        </td>
    </tr>
</table>
<div style="width:100%; float:left;">
    <div class="hide_show_userpassword" style="float:left; margin-bottom:10px;">
    	<div class="button nomargin">Byt lösenord</div>
    </div>
</div>
<div id="customerinfo">
    <table class="settingstable">
        <tr>
            <td class="settingstitle">
                Nytt lösenord
            </td>
            <td class="settingsfield">
        		<input type="password" tabindex="4" class="textfield" id="user_password" name="user_password" onchange="javascript: setChanged(customerform);" value="" />
            </td>
        </tr>
        <tr>
            <td class="settingstitle">
                Nytt lösenord igen
            </td>
            <td class="settingsfield">
        		<input type="password" tabindex="5" class="textfield" id="user_password_again" name="user_password_again" onchange="javascript: setChanged(customerform);" value="" />
            </td>
        </tr>
    </table>
</div>
<input tabindex="6" type="submit" value="Spara" class="submitbutton" />

</form>