<?PHP
// Ett simpelt plugin

### Körs vid installation
function zc_imagegallery_install()
{
	$folder = "../upload/zc_imagegallery";
	if(!is_dir($folder))
	{
		mkdir($folder);
	}
}

### Körs vid avinstallation
function zc_imagegallery_uninstall()
{
	/*$folder = "../upload/zc_imagegallery";
	if(is_dir($folder))
	{
		removeDir($folder);
	}*/
}

### Körs vid aktivering
function zc_imagegallery_activate()
{
	
}

### Körs vid avaktivering
function zc_imagegallery_inactivate()
{
	
}

### Körs på varje sida på hemsidan när pluginet läses in
function zc_imagegallery_init_page()
{
	zc_add_page("zc_imagegallery", "imagegallery.php", "Bildgalleri");
	zc_add_action("htmlincludes", "zc_imagegallery_printhead");
}

### Körs på varje sida i administrationspanelen när pluginet läses in
function zc_imagegallery_init_admin()
{
	zc_add_menulink("publish_menu", "Ladda upp bilder", "plugin/zc_imagegallery/upload");
	zc_add_menulink("publish_menu", "Hantera bildgalleri", "plugin/zc_imagegallery/");
	zc_add_menulink("publish_menu", "Skapa nytt bildgalleri", "plugin/zc_imagegallery/newgallery");
}

### Körs när man går in på pluginet i adminpanelen (http://website.com/admin/plugin/yourplugin)
function zc_imagegallery_show_adminpage()
{
	$page = "start";
	if(defined("OPT2"))
	{
		$page = safeText(OPT2);
	}
	switch($page)
	{
		case "start":
			$gallerydir = "../upload/zc_imagegallery/";
			$dir = "";
			$showStart = true;
			$title = "Hantera bilder";
			$filetypes = 'jpg,gif,png,jpeg';
			$types = explode(',', $filetypes);
			$dirslug = "0";
			
			if(defined("OPT3"))
			{
				$temp = safeText(OPT3);
				if(is_dir($gallerydir . $temp))
				{
					$dir = $gallerydir . $temp . "/";
					$showStart = false;
					$title .= " i mappen " . zc_imagegallery_getGalleryName($temp);
					$dirslug = $temp;
				}
			}	
			$_SESSION['return'] = THIS_PAGE;
			?>
            <style type="text/css">
			@import "<?PHP echo HOST . "plugins/zc_imagegallery/adminstyle.css"; ?>";
			</style>
            <script src="<?PHP echo HOST . "plugins/zc_imagegallery/script.js"; ?>" type="text/javascript"></script>
            <div id="image_info_dialog" class="popup" title="Infomartion om bilden">Bildens sökväg: <span style="font-weight:bold; font-size:8pt;" id="image_info_title"></span><br /><br /><span id="image_info_image"><img src="" style="width:380px; max-height:380px;" /></span></div>
            <div id="gallery_delete_dialog" class="popup" title="Säkerhetsfråga">Är du säker på att du vill radera hela galleriet <strong><?PHP echo zc_imagegallery_getGalleryName($dirslug); ?></strong>?<br />Alla bilder i galleriet kommer att raderas från hemsidan.</div>
            <div class="pagetitle"><?PHP echo $title; ?></div>
            <table class="settingstable">
                <tr>
                    <td class="settingstitle">
                        Välj galleri att hantera
                    </td>
                    <td class="settingsfield">
                        <div class="dropbox">
                            <select name="category" class="dropdown" id="category_dropdown">
                                <option value="">Välj mapp</option>
                                <?PHP
                                if (is_dir($gallerydir))
                                {
                                    $dirs = glob($gallerydir . "*");
                                    foreach($dirs as $d)
                                    {
                                        if(is_dir($d))
                                        {
                                            $dirname = substr(strrchr($d, "/"), 1);
                                            echo "<option value=\"{$dirname}\"";
											setSel($dirslug, $dirname);
											echo ">" . zc_imagegallery_getGalleryName($dirname) . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div style="width:400px; float:left;"></div>
                        <?PHP if($dirslug != "0")
						{
							?>
                            <div class="button right" id="delete_gallery" onclick='javascript: securityAskDeleteGallery("<?PHP echo $dirslug; ?>", "<?PHP echo zc_imagegallery_getGalleryName($dirslug); ?>");'>Radera galleri</div>
                            <?PHP
						}
						?>
                    </td>
                </tr>
            </table>
			<?PHP
			$i = 0;
			if (is_dir($dir))
			{
				echo "<div id='gallerycontainer' style='width:100%;'>";
				$files = glob($dir . "*");
				foreach($files as $file)
				{
					$file_ext = pathinfo($file, PATHINFO_EXTENSION);
					$filename = basename($file);
					if(in_array(strtolower($file_ext), $types))
					{
						echo "<img src='" . HOST . "upload/zc_imagegallery/{$dirslug}/{$filename}' class='gallerythumb' onclick='javascript: showImageInfo(\"{$dirslug}\", \"{$filename}\",this);'>";
						$i++;
					}
				}
				echo "</div>";
			}
			if($i == 0 && !$showStart)
			{
				echo "Tyvärr finns det inga bilder här än.";
			}
			elseif($showStart)
			{
				echo "Välj ett galleri för att se lite bilder...</span>";
			}
			break;
		case "upload":
			?>
            <div class="pagetitle">Ladda upp bilder</div>
            <div id="flashContent">
                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="790" height="400" id="ImageUpload" align="middle">
                    <param name="movie" value="<?PHP host(); ?>plugins/zc_imagegallery/flash/ImageUpload.swf?time=<?PHP echo date("his"); ?>" />
                    <param name="quality" value="high" />
                    <param name="bgcolor" value="#eaeaea" />
                    <param name="play" value="true" />
                    <param name="loop" value="true" />
                    <param name="wmode" value="window" />
                    <param name="scale" value="showall" />
                    <param name="menu" value="true" />
                    <param name="devicefont" value="false" />
                    <param name="salign" value="" />
                    <param name="allowScriptAccess" value="sameDomain" />
                    <param name="flashvars" value="host=<?PHP host(); ?>" />
                    <!--[if !IE]>-->
                    <object type="application/x-shockwave-flash" data="<?PHP host(); ?>plugins/zc_imagegallery/flash/ImageUpload.swf?time=<?PHP echo date("his"); ?>" width="790" height="400">
                        <param name="movie" value="<?PHP host(); ?>plugins/zc_imagegallery/flash/ImageUpload.swf?time=<?PHP echo date("his"); ?>" />
                        <param name="quality" value="high" />
                        <param name="bgcolor" value="#eaeaea" />
                        <param name="play" value="true" />
                        <param name="loop" value="true" />
                        <param name="wmode" value="window" />
                        <param name="scale" value="showall" />
                        <param name="menu" value="true" />
                        <param name="devicefont" value="false" />
                        <param name="salign" value="" />
                        <param name="allowScriptAccess" value="sameDomain" />
                    	<param name="flashvars" value="host=<?PHP host(); ?>" />
                    <!--<![endif]-->
                        <form action="<?PHP host(); ?>admin/plugin/zc_imagegallery/save" enctype="multipart/form-data" method="post" id="customerform">
                        <table class="settingstable">
                            <tr>
                                <td class="settingstitle">
                                    Bild
                                </td>
                                <td class="settingsfield">
                                    <input tabindex="1" type="file" class="textfield" id="uplfile" name="uplfile" accept="image/jpeg" onchange='javascript: setChanged(customerform);' />
                                </td>
                            </tr>
                            <tr>
                                <td class="settingstitle">
                                    Välj mapp att ladda upp till
                                </td>
                                <td class="settingsfield">
                                    <div class="dropbox">
                                        <select name="category" class="dropdown" id="category">
                                            <option value="">Välj mapp</option>
                                            <?PHP
                                            $dir = "../upload/zc_imagegallery/";
                                            if (is_dir($dir))
                                            {
                                                $dirs = glob($dir . "*");
                                                foreach($dirs as $d)
                                                {
                                                    if(is_dir($d))
                                                    {
                                                        $dirname = substr(strrchr($d, "/"), 1);
                                                        echo "<option value=\"{$dirname}\">" . zc_imagegallery_getGalleryName($dirname) . "</option>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <input tabindex="4" type="submit" value="Spara" class="submitbutton" style="cursor:pointer;" />
                        </form>
                    <!--[if !IE]>-->
                    </object>
                    <!--<![endif]-->
                </object>
            </div>
            <?PHP
			break;
		case "save":
			if(isset($_POST['category']) && $_POST['category'] != "")
			{
				$cat = safeText($_POST['category']);
				$upload = false;
				if (isset($_FILES['uplfile'])) 
				{
					// Mappen där filerna ska hamna
					$upload_dir = "../upload/zc_imagegallery/{$cat}/";
					// De tillåtna filtyperna, separerade med komman, utan mellanrum
					$filetypes = 'jpeg,jpg,gif,png';
					// Den största tillåtna storleken (64 MB)
					$maxsize = (1024*64000);
				
					// Kontrollera att det angavs en fil
					if(empty($_FILES['uplfile']['name']))
						adminerror('Ingen fil har valts');
				
					// Kontrollera storleken
					if($_FILES['uplfile']['size'] > $maxsize)
						adminerror('Filen du valde är för stor. Maxstorleken är '.(string)($maxsize/1024).' KB.');
				
					// Kontrollera filtypen
					$types = explode(',', $filetypes);
					$file = explode('.', $_FILES['uplfile']['name']);
					$extension = $file[sizeof($file)-1];
					if(!in_array(strtolower($extension), $types))
						adminerror('Du har en felaktig filtyp. Endast .jpg, .jpeg, .png och .gif är tillåtet!');
				
					$thefile = date("YmdHis") . "_" . basename($_FILES['uplfile']['name']);
					
					//print_r($_FILES);
				
					// Flytta filen rätt
					if (move_uploaded_file($_FILES['uplfile']['tmp_name'],$upload_dir.$thefile)) {
						$upload = true;
						
						$imgsize = ImageSize($upload_dir.$thefile);
						if($imgsize['width'] > 800)
						{
							// Create a smaller image
							resizeImage($upload_dir.$thefile, 800, $thefile, $upload_dir, true);
						}
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
					go(HOST . "admin/plugin/zc_imagegallery/result/Succe");
				}
				else
				{
					go(HOST . "admin/plugin/zc_imagegallery/result/Fail");
				}
			}
			else
			{
				adminerror("Kategori saknas");
			}
			break;
		case 'result':
			if(defined("OPT3"))
			{
				if(OPT3 == "Succe")
				{
					$title = "Bilduppladdningen lyckades!";
                    $text = "Bilden finns nu i det galleri du valde.";
				}
				elseif(OPT3 == "Fail")
				{
					$title = "Bilduppladdningen misslyckades!";
                    $text = "Gå tillbaka och försök igen.";
				}
				elseif(OPT3 == "GallerySucce")
				{
					$title = "Galleriet har skapats!";
                    $text = "Du kan nu ladda upp bilder till det genom att välja \"Ladda upp bilder\" i Publicera-menyn.";
				}
				elseif(OPT3 == "GalleryDeleted")
				{
					$title = "Radering lyckades!";
					$text = "Galleriet och dess bilder har nu raderats.";
				}
				else
				{
					$title = "Här finns inget att se";
					$text = "Jag vet inte riktigt vad du gör här men det finns inget att se här.";
				}
                echo "<div class='pagetitle'>{$title}</div>";
                echo "<div class='description'>{$text}</div>";
			}
			else
			{
				adminerror(404);
			}
			break;
		case "newgallery":
			?>
            <div class="pagetitle">Skapa nytt bildgalleri</div>
            <div class="description">
            Här skapar du ett nytt galleri som du sedan kan ladda upp bilder till.<br />
            <form action="<?PHP host(); ?>admin/plugin/zc_imagegallery/creategallery" method="post" id="customerform">
                <table class="settingstable">
                    <tr>
                        <td class="settingstitle">
                            Namn
                        </td>
                        <td class="settingsfield">
                            <input tabindex="1" type="text" class="textfield" id="name" name="name" onchange='javascript: setChanged(customerform);' />
                        </td>
                    </tr>
                </table>
                <input tabindex="4" type="submit" value="Skapa" class="submitbutton" style="cursor:pointer;" />
            </form>
            </div>
            <?PHP
			break;
		case "creategallery":
			$gallerydir = "../upload/zc_imagegallery/";
			if(p("name") != false && trim(p("name")) != "")
			{
				$name = p("name");
				$slug = getSlug($name);
				$url = $gallerydir . $slug;
				if(!is_dir($url))
				{
					mkdir($url);
					$file = $url . "/name.txt";
					$f = fopen($file, 'w');
					fwrite($f, $name);
					fclose($f);
					go(HOST . "admin/plugin/zc_imagegallery/result/GallerySucce");
				}
				else
				{
					adminerror("Det finns redan ett galleri med det namnet.");
				}
			}
			else
			{
				adminerror("Du måste ange ett namn på galleriet du vill skapa.");
			}
			break;
		case "deletegallery":
			$gallerydir = "../upload/zc_imagegallery/";
			
			if(defined("OPT3"))
			{
				$temp = safeText(OPT3);
				if(is_dir($gallerydir . $temp))
				{
					removeDir($gallerydir . $temp);
					go(HOST . "admin/plugin/zc_imagegallery/result/GalleryDeleted");
				}
				else
				{
					adminerror("Du försöker ta bort ett galleri som inte finns.");
				}
			}
			else
			{
				adminerror(404);
			}
			break;
		default:
			adminerror(404);
	}
}

function zc_imagegallery_printhead()
{
	$pluginfolder = HOST . "plugins/zc_imagegallery/";
	?>
    <link href="<?PHP echo $pluginfolder; ?>highslide/highslide.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?PHP echo $pluginfolder; ?>highslide/highslide-with-gallery.js"></script>
	<script type="text/javascript" src="<?PHP echo $pluginfolder; ?>jquery.masonry.min.js"></script>
    <?PHP
	
}

function zc_imagegallery_getGalleryName($folder)
{
	$file = "../upload/zc_imagegallery/{$folder}/name.txt";
	$f = fopen($file,'r');
	$line = fgets($f);
	fclose($f);
	return $line;
}

function zc_imagegallery_getGalleryNameOnpage($folder)
{
	$file = "upload/zc_imagegallery/{$folder}/name.txt";
	$f = fopen($file,'r');
	$line = fgets($f);
	fclose($f);
	return $line;
}

?>