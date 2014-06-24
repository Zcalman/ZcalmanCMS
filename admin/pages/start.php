<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
?>
<div class="pagetitle">Välkommen <?PHP echo $_SESSION['name']; ?>!</div>
<div class="description">
Det här är din kontrollpanel.
</div>