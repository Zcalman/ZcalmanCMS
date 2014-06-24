<?PHP
require("autoUpdateTop.php");
$html = "<table>";

$dir = "../../upload/images";

// Open a known directory, and proceed to read its contents
if (is_dir($dir))
{
    if ($dh = opendir($dir))
	{
		$i = 0;
        while (($file = readdir($dh)) !== false)
		{
			if (($file == '.')||($file == '..'))
            {
            }
			elseif($file == 'Thumbs.db') 
			{
			}
			elseif($file == 'thumbs') 
			{
			}
			else 
			{
				$imgsize = ImageSize( HOST ."upload/images/". $file);
            	$html .= "<tr><td class='GalleryThumb'><img src='". HOST ."upload/images/thumbs/$file' alt='' class='thumbnail' /></td><td class='GalleryText'><p>Storlek: {$imgsize['width']}x{$imgsize['height']}</p></td><td class='ButtonBox'><input type='button' class='submitbutton' value='V&auml;lj' onclick='javascript: ChooseImage(\\\"". HOST ."upload/images/{$file}\\\");'/></td></tr>";
				$i++;
			}
        }
        closedir($dh);
    }
}
$html .= "</table>";

if($html == "<table></table>")
{
	$html = "<p>Kunde inte hitta n&aring;gra bilder...</p>";
}
require("autoUpdateBottom.php");
?>

var targetDiv = document.getElementById("GalleryDialogContent");
targetDiv.innerHTML = "<?PHP echo $html; ?>";

StopLoadingbar();