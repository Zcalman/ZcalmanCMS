<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
?>
    </div>
    <div id="footer">
    	<p><?PHP echo $cms_version_text; ?></p>
    	<a href="http://zcalman.se" target="_blank">&copy; Zcalman <?PHP echo date("Y"); ?></a>
    </div>
</div>
<?PHP include("dialogs.php"); ?>
</body>
</html>
