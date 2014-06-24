<?PHP
require("autoUpdateTop.php");

$document = safeText(g('document'));
$id = safeText(g('docid'));
$file = "../../upload/documents/" . $document;
if(isRights(1,2))
{
	$sql = mysql_query("SELECT file FROM documents WHERE id = {$id} LIMIT 1");
	$r = mysql_fetch_assoc($sql);
	if($r['file'] == $document)
	{
		if(file_exists($file))
		{
			$query = mysql_query("DELETE FROM documents WHERE id = {$id} LIMIT 1");
			unlink($file);
		}
		else
		{
			$query = mysql_query("DELETE FROM documents WHERE id = {$id} LIMIT 1");			
		}
	}
	else
	{
		echo "Ett fel har uppstått mellan databasen och servern.\\nKontakta Niclas via niclas@zcalman.se och beskriv problemet.";
	}
}

require("autoUpdateBottom.php");
?>
