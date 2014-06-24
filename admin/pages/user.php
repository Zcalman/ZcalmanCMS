<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

rightsToSee(1);
$userid = "Nytt konto";
$username = "";
$useremail = "";
$userclass = "";
$userusername = "";
$titleusername = "Ny användare";
$oldUser = false;
if(defined("OPT1"))
{
	$s = safeText(OPT1);
	$s = (int)$s;
	$query = mysql_query("SELECT * FROM userbase WHERE id = {$s} LIMIT 1");
	if($query == false || mysql_num_rows($query) == 0)
	{
		error(404);
	}
	$r = mysql_fetch_assoc($query);
	$userid = $r['id'];
	$userusername = $r['username'];
	$username = $r['name'];
	$useremail = $r['email'];
	$userclass = $r['userclass'];
	$titleusername = "Editera användare";
	$oldUser = true;
}
$_SESSION['return'] = $_SESSION['last_page'];
?>
<script type="text/javascript">

<?PHP if($oldUser)
{
	?>
    $(document).ready(function() {
        $('#customerinfo').hide();
    });
    <?PHP 
}
else
{
	?>
    $(document).ready(function() {
		$('.hide_show_userpassword').hide();
    });
	<?PHP
}
?>
</script>
<?PHP if(defined("OPT2"))
{
	echo "<div class=\"messagebox\">" . safeText(OPT2) . "</div>";
}
?>
<div class="pagetitle">
<?PHP echo $titleusername; ?>
</div>
<form action="<?PHP host(); ?>admin/save/user" method="post" id="customerform" onsubmit='javascript: return validateUser(this);'>
<input type="hidden" name="userid" id="userid" value="<?PHP echo $userid; ?>" />
<table class="settingstable">
    <tr>
        <td class="settingstitle">
            Användarnamn
        </td>
        <td class="settingsfield">
    		<input tabindex="1" type="text" class="textfield" name="username" id="username" onchange="javascript: setChanged(customerform);" value="<?PHP echo $userusername; ?>" />
        </td>
    </tr>
    <tr>
        <td class="settingstitle">
            Namn
        </td>
        <td class="settingsfield">
            <input tabindex="2" type="text" class="textfield" name="name" id="name" onchange="javascript: setChanged(customerform);" autocomplete="off" value="<?PHP echo $username; ?>" />
        </td>
    </tr>
    <tr>
        <td class="settingstitle">
            E-postadress
        </td>
        <td class="settingsfield">
    		<input tabindex="3" type="text" class="textfield" name="email" id="email" onchange="javascript: setChanged(customerform);" value="<?PHP echo $useremail; ?>" />
        </td>
    </tr>
    <tr>
        <td class="settingstitle">
            Användarklass
        </td>
        <td class="settingsfield">
            <div class="dropbox">
                <select name="userclass" tabindex="4" id="userclass" onchange="javascript: setChanged(customerform);" class="dropdown">
                    <option <?PHP setSel($userclass, 2); ?>value="2"><?PHP echo $writerclassname; ?></option>
                    <option <?PHP setSel($userclass, 1); ?>value="1"><?PHP echo $administratorclassname; ?></option>
                </select>
            </div>
        </td>
    </tr>
</table>
<div style="width:100%; float:left;">
    <div class="hide_show_userpassword" style="float:left; margin-bottom:10px;">
    	<div class="button nomargin">Byt användarens lösenord</div>
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
<?PHP if($oldUser)
{
	?>
        <div class="button right" onclick='securityAskDeleteUser("<?PHP echo $userusername . '","' . $userid;?>")'>
            Radera
        </div>
    <?PHP
}
?>
</form>