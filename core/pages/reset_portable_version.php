<?PHP
if(isset($_SESSION['use_mobile_version']))
{
	$_SESSION['use_mobile_version'] = false;
	unset($_SESSION['use_mobile_version']);
}
header("Location: ". HOST . "");
?>