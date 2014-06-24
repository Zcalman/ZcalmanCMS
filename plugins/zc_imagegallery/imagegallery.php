<?PHP
zc_page_top();

$gallerydir = "upload/zc_imagegallery/";
$dir = "";
$showStart = true;
$title = "Bildgalleri";
$filetypes = 'jpg,gif,png,jpeg';
$types = explode(',', $filetypes);
$dirslug = "0";

if(defined("OPT1"))
{
	$temp = safeText(OPT1);
	if(is_dir($gallerydir . $temp))
	{
		$dir = $gallerydir . $temp;
		$showStart = false;
		$title .= " - " . zc_imagegallery_getGalleryNameOnpage($temp);
		$dirslug = $temp;
	}
}
?>
<script type="text/javascript">
hs.graphicsDir = '<?PHP host(); ?>plugins/zc_imagegallery/highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'rounded-white';
hs.fadeInOut = true;
//hs.dimmingOpacity = 0.75;

// Add the controlbar
hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: true,
	overlayOptions: {
		opacity: .6,
		position: 'bottom center',
		hideOnMouseOut: true
	}
});

$(document).ready( function(){
	var $container = $('#gallerycontainer');
	$container.imagesLoaded(function(){
		$container.masonry({
			itemSelector: '.gallerythumb',
			columnWidth: 140,
			gutterWidth: 20,
			gutterHeight: 20
		});
	});
	
	$('#gallery_sel').change(function(){
		top.location.href = host + "zc_imagegallery/" + $('#gallery_sel').val();
	});
});

</script>
<div id="middlecontent" style="width:790px;">
	<h1 class="pagetitle" style="width:775px;"><?PHP echo $title; ?></h1>
    <div class="gallerychoose">
        Visa galleri:
        <select name="gallery_sel" id="gallery_sel">
        <option value="">Välj...</option>
        <?PHP
        if (is_dir($gallerydir))
        {
            $dirs = glob($gallerydir . "*");
            foreach($dirs as $d)
            {
                if(is_dir($d))
                {
                    
                    $dirname = substr(strrchr($d, "/"), 1);
                    echo "<option value='{$dirname}' ";
					if($dirname == $dirslug)
					echo "selected=\"selected\"";
					echo ">" . zc_imagegallery_getGalleryNameOnpage($dirname) . "</option>";
                }
            }
        }
        ?>
        </select>
    </div>

<?PHP
// Open a known directory, and proceed to read its contents
$i = 0;
if (is_dir($dir))
{
    if ($dh = opendir($dir))
	{
		echo "<div id='gallerycontainer' style='width:100%;'>";
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
				$file_ext = pathinfo($file, PATHINFO_EXTENSION);
				if(in_array(strtolower($file_ext), $types))
				{
					//if($i%5 == 0)
					//echo "</tr><tr>";
					
					echo "<a href='" . HOST . $dir ."/{$file}' onclick='return hs.expand(this)' class='gallerythumb'><img src='" . HOST . $dir ."/{$file}'></a>";
					//echo "<td><a href='" . HOST . $dir ."/{$file}' onclick='return hs.expand(this)'><img src='" . HOST . $dir ."/{$file}' class='gallerythumb'></a></td>";
					$i++;
				}
			}
        }
        closedir($dh);
		echo "</div>";
    }
}
if($i == 0 && !$showStart)
{
	echo "Tyvärr finns det inga bilder här än.";
}
elseif($showStart)
{
	echo "Välj ett galleri för att se lite bilder...</span>";
}

?>
</div> 
<?PHP
zc_page_bottom();
?>