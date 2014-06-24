<?PHP 
if(defined("OPT2"))
{
	$nr = safeText(OPT2);
	$errorfile = get_errorpage($nr);
	include($errorfile);
}
?>