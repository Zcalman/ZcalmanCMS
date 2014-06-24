<?PHP
if(isset($_SESSION['use_mobile_version']))
{
	$_SESSION['use_mobile_version'] = true;
}
$retur = "";
if(defined("OPT1"))
{
	$retur = str_replace("|", "/", OPT1);
}
header("Location: " . HOST . $retur . "");
?>