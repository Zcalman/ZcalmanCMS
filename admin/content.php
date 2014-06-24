<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
?>
    <div id="content">
    	<div id="saving">
        	<img src="<?PHP adminhost(); ?>images/loader.gif" /><p>Sparar</p>
        </div>
    <?PHP 
	include(CONTENT);
	?>
    </div>