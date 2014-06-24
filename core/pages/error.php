<?PHP 
if(defined("OPT1"))
{
	$nr = safeText(OPT1);
	$errorfile = get_errorpage($nr);
	include($errorfile);
}
?>