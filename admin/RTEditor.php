<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

$text = preg_replace('/(\r\n|\n|\r|\f)/U', "", $text);

// Fix for wierd space char from MAC Word.
$text = utf8_encode($text);
$text = str_replace("â¨", " ", $text);

?>
<!-- Include the Free Rich Text Editor Runtime -->
<script src="<?PHP adminhost(); ?>script/RTE/js/richtext.js" type="text/javascript"></script>
<!-- Include the Free Rich Text Editor Variables Page -->
<script src="<?PHP adminhost(); ?>script/RTE/js/config.js" type="text/javascript"></script>
<!-- Initialise the editor -->
<script type="text/javascript">
initRTE('<?PHP echo $text; ?>', '<?PHP adminhost(); ?>stylesheets/RTEditor.style.css');
</script>