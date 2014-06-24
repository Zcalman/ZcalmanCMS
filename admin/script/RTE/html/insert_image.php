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
defineHOST();
$host = HOST;
protect();
defineOptAndThisPage();
defineLanguage();
include("../../../../core/language/" . LANGUAGE . ".php");
$upload = false;
if (isset($_FILES['uplfile'])) {
	// Mappen där filerna ska hamna
	$upload_dir = "../../../../upload/images/";
	// De tillåtna filtyperna, separerade med komman, utan mellanrum
	$filetypes = 'jpg,gif,png';
	// Den st�rsta tillåtna storleken (20 mB)
	$maxsize = (1024*20000);

	// Kontrollera att det angavs en fil
	if(empty($_FILES['uplfile']['name']))
		die('Ingen fil har valts');

	// Kontrollera storleken
	if($_FILES['uplfile']['size'] > $maxsize)
		die('Filen du valde är för stor. Maxstorleken är '.(string)($maxsize/1024).' KB.');

	// Kontrollera filtypen
	$types = explode(',', $filetypes);
	$file = explode('.', $_FILES['uplfile']['name']);
	$extension = $file[sizeof($file)-1];
	if(!in_array(strtolower($extension), $types))
		die('Du har en felaktig filtyp. Endast .jpg och .gif är tillåtet!');

	// Generera unikt och scriptsäkert filnamn
	$bokstav = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6');
	$thefile = $_FILES['uplfile']['name'];
	while (file_exists($upload_dir.$thefile)) { $thefile = $bokstav[rand(0, count($bokstav)-1)].$thefile; }
	$thefile = getSlug($thefile);
	$thefile = substr($thefile, 0, strlen($thefile)-strlen($extension));
	$thefile = $thefile . "." . $extension;

	// Flytta filen rätt
	if (is_uploaded_file($_FILES['uplfile']['tmp_name']) && move_uploaded_file($_FILES['uplfile']['tmp_name'],$upload_dir.$thefile)) {
		$upload = true;
		
		$imgsize = ImageSize($upload_dir.$thefile);
		
		if($imgsize['width'] > $thumbimagesize || $imgsize['height'] > $thumbimagesize)
		{
			// Create thumbnail
			resizeImage($upload_dir.$thefile, $thumbimagesize, $thefile, $upload_dir. "thumbs/", true);
		}
		
		if($imgsize['width'] > $imagemaxsize || $imgsize['height'] > $imagemaxsize)
		{
			// Create a smaller image
			resizeImage($upload_dir.$thefile, $imagemaxsize, $thefile, $upload_dir, true);
		}
	}
	else {
		/*
		Uppladdningen misslyckades.
		*/
		$upload = false;
	}
}

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
	function AddImage() {
		if (document.getElementById("url").value != "") {
				var html = "";
						html += '<img src="' + document.getElementById("url").value + '"';
					if (document.getElementById("alt").value != "") {
						html += ' alt="' + document.getElementById("alt").value + '"';
						html += ' title="' + document.getElementById("alt").value + '"';
					}
						html += ' />';
			window.opener.rteInsertHTML(html);
			window.close();
		} else {
			alert('Ingen bild vald!');
		}
	}
	function StopLoadingbar()
	{
		$('#loadingbar').hide();
	}
	function ShowGallery()
	{
		$('#GalleryDialog').dialog({modal: true, resizable: false, width: 550, height: 520, buttons: { "Avbryt": function() { $(this).dialog("close"); } } });
		attach_file("../../../autoupdate/getGalleryImages.php?time=<?PHP echo date("YmdHis"); ?>");
	}
	function ChooseImage(img)
	{
		$('#url').val(img);
		$('#GalleryDialog').dialog("close");
	}
	$(document).ready(function() {
	<?PHP if($upload)
	{
		echo "AddImage();";
	}
	?>
	});
</script>
<style>
#GalleryDialog {
	display:none;
}
#loadingbar {
	margin-top:20px;
}
#loadingbar img {
	float:left;
}
#loadingbar p {
	float:left;
	margin-left:10px;
	margin-top:6px;
	font-weight:bold;
	color:#1869a8;
}
#GalleryDialog table {
	border:1px #999999 solid;
	width:500px;
}
#GalleryDialog tr {
	background-color:#d4d0cd;
}
#GalleryDialog td {
	background-color:#d4d0cd;
	padding:5px;
}
.GalleryThumb {
	width:120px;
	max-height:120px;
	float:left;
}
.GalleryThumb .thumbnail {
	max-width:120px;
	max-height:120px;
}
.GalleryText {
	float:left;
	width:140px;
	overflow:hidden;
}
.GalleryText p {
	font-size:9pt;
	width:140px;
	margin:0;
	font-weight:normal;
	color:#666;
}
.ButtonBox {
	width:80px;
}
</style>
</head>
<body>
<h1>Lägg till bild</h1>
<div id="WindowContent">
    <form name="newImg" method="post" enctype="multipart/form-data" action="<?PHP $_SERVER['PHP_SELF'];?>">
		<div class="frame">
        	<h2>Ladda upp ny bild</h2>
            <table border="0" cellpadding="0" cellspacing="2">
                <tbody>
                    <tr>
                    	<td style="width:150px;">Bild</td>
                    	<td><input type="file" name="uplfile" class="textfield" style="height: 28px;" /></td>
                    </tr>
                    <tr>
                    	<td>Bild beskrivning</td>
                    	<td><input id="alttext" name="alttext" type="text" class="textfield" style="width:255px;" /></td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" class="submitbutton" name="Submit" value="Ladda upp bild" />
        </div>
    </form>
	<div class="frame">
        <input type="hidden" value="" id="align">
        <input type="hidden" value="" id="border">
        <input type="hidden" value="" id="hspace">
        <input type="hidden" value="" id="vspace">
        <h2>Välj bild från arkivet</h2>
        <div class="button" onclick="ShowGallery();">Öppna akrivet</div>
        <table border="0" cellpadding="0" cellspacing="2">
            <tbody>
                <tr>
                	<td style="width:150px;">Bild URL</td>
                	<td><input id="url" type="text" class="textfield" value="<?PHP if($upload){host(); echo "upload/images/{$thefile}";}?>" style="width:255px;"></td>
                </tr>
                <tr>
                	<td>Bild beskrivning</td>
                	<td><input id="alt" type="text" class="textfield" value="<?PHP if($upload){echo $_POST['alttext'];}?>" style="width:255px;"></td>
                </tr>
            </tbody>
        </table>
        <input type="submit" class="submitbutton" name="Submit" value="L&auml;gg till bild" onclick="AddImage();">
	</div>
</div>
<div id="GalleryDialog" title="V&auml;lj en blid fr&aring;n arkivet">
	<div id="loadingbar">
    	<img src="<?PHP adminhost(); ?>images/loader.gif" /><p>L&auml;ser in bilder</p>
    </div>
    <div id="GalleryDialogContent">
    </div>
</div>
</body>
</html>