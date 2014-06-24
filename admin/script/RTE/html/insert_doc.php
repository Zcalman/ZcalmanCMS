<?PHP
// Ställer in tidszon
date_default_timezone_set("Europe/Stockholm");

require("../../../../zc_settings.php");
global $thumbimagesize;
session_start();
$objConn = mysql_connect ($dbhost, $dbuser, $dbpass);
mysql_select_db($db);
require("../../../../core/core_functions.php");
require("../../../admin_functions.php");
require("../../../fileIcons.php");
require("../../../classes/Table.class.php");
require("../../../classes/Row.class.php");
defineHOST();
$host = HOST;
protect();
defineOptAndThisPage();
defineLanguage();
include("../../../../core/language/" . LANGUAGE . ".php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="../../../stylesheets/custom-theme/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css" />
<link href="../../../stylesheets/table.style.css" rel="stylesheet" type="text/css" />
<link href="../../../stylesheets/style.css" rel="stylesheet" type="text/css" />
<link href="../../../stylesheets/popup.style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
<script src="../../script.js" type="text/javascript"></script>
<script>
	function addDocument(file) {
		if (file != "") {
				var html = "";
						html += '<a href="<?PHP echo $host; ?>upload/documents/' + file + '" title="&Ouml;ppna ' + file + '">' + file + '</a>';
			window.opener.rteInsertHTML(html);
			window.close();
		} else {
			alert('Inget dokument valt!');
		}
	}
	function StopLoadingbar()
	{
		$('#loadingbar').hide();
	}
	function ChooseImage(img)
	{
		$('#url').val(img);
		$('#GalleryDialog').dialog("close");
	}
</script>
</head>
<body>
<h1>Dokument</h1>
<br />
<div id="WindowContent">
	<?PHP
	$sql = mysql_query("SELECT * FROM documents");
	$docs = new Table(2, "Fil| ", "682|110", "");
	while($r = mysql_fetch_assoc($sql))
	{
		$icon = "<img src='" . getFileIcon($r['type']) . "' alt='' style='margin-top:2px;' />";
		$link = "<a href='javascript: addDocument(\"{$r['file']}\");'>Välj</a>";
		$docs->addRow($r['file'], $icon . " {$r['file']}|{$link}");
	}
	$docs->printTable();
	?>
</div>
</body>
</html>