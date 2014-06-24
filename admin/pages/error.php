<?PHP 
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
$return = false;
if(isset($_SESSION['error_return_page']))
{
	$return = $_SESSION['error_return_page'];
	unset($_SESSION['error_return_page']);
}

if(defined("OPT1"))
{
	$nr = (int)OPT1;
	$errorfile = $nr . ".php";
	if(file_exists("pages/". $errorfile))
	{
		include("pages/" . $errorfile);
	}
	else
	{
		$text = safeText(OPT1);
		?>
        <div class="pagetitle">Felmeddelande</div>
		<div class="description"><?PHP echo $text; ?></div>
		<?PHP
	}
	
	if($return != false)
	{
		?>
        <div class="button" style="margin-top:20px;" onclick='javascript:goto("<?PHP echo $return; ?>")'>Tillbaka</div>
        <?PHP
		
	}
	else
	{
		?>
        <div class="button" style="margin-top:20px;" onclick='javascript:history.back()'>Tillbaka</div>
        <?PHP
		
	}
}
?>