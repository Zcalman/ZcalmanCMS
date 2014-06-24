<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

rightsToSee(1,2);
$upload = false;
if (isset($_FILES['uplfile'])) {
	// Mappen där filerna ska hamna
	$upload_dir = "../upload/documents/";
	// De tillåtna filtyperna, separerade med komman, utan mellanrum
	$filetypes = 'jpg,jpeg,gif,png,bmp,doc,docx,pps,ppsx,ppt,pptx,xls,xlsx,pdf,rar,zip,swf,flv,txt';
	// Den st�rsta tillåtna storleken (200 mb)
	$maxsize = (1024*1024*200);

	// Kontrollera att det angavs en fil
	if(empty($_FILES['uplfile']['name']))
		die('Ingen fil har valts');

	// Kontrollera storleken
	if($_FILES['uplfile']['size'] > $maxsize)
		die('Filen du valde är för stor. Maxstorleken är '.(string)(($maxsize/1024)/1024).' MB.');

	// Kontrollera filtypen
	$types = explode(',', $filetypes);
	$file = explode('.', $_FILES['uplfile']['name']);
	$extension = strtolower($file[sizeof($file)-1]);
	if(!in_array($extension, $types))
		die('Du har en felaktig filtyp.');

	// Generera unikt och scriptsäkert filnamn
	$bokstav = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6');
	$thefile = $_FILES['uplfile']['name'];
	$thefile = substr($thefile, 0, strlen($thefile)-strlen($extension));
	$thefile = getSlug($thefile);
	$thefile = $thefile . "." . $extension;
	while (file_exists($upload_dir.$thefile))
	{
		$thefile = $bokstav[rand(0, count($bokstav)-1)].$thefile;
	}
	$filesize = $_FILES['uplfile']['size'];

	// Flytta filen rätt
	if (is_uploaded_file($_FILES['uplfile']['tmp_name']) && move_uploaded_file($_FILES['uplfile']['tmp_name'],$upload_dir.$thefile))
	{
		$upload = true;
		$sql = mysql_query("INSERT INTO documents (file,type,size) VALUES ('{$thefile}', '{$extension}', {$filesize})");
	}
	else
	{
		/*
		Uppladdningen misslyckades.
		*/
		$upload = false;
	}
}

if($upload)
{
	?>
	<h2>Filuppladdningen lyckades!</h2><br />
	<p><strong>Fil:</strong> <img src="<?PHP echo getFileIcon($extension); ?>" alt="" /> <a href="<?PHP host(); ?>upload/documents/<?PHP echo $thefile; ?>" title="Öppna filen" target="_blank"><?PHP host(); ?>upload/documents/<?PHP echo $thefile; ?></a><br />
	<strong>Filstorlek:</strong> <?PHP echo getFileSize($filesize); ?></p>
    <p><a href="<?PHP adminhost(); ?>documents">Tillbaka till dokumentlistan</a></p>
	<?PHP
}
else
{
	?>
    
    <?PHP
}
?>

