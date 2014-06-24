<?PHP
// Ett simpelt plugin

### Körs vid installation
function zc_partners_install()
{
	if(!zc_partners_table_exists("zc_partners"))
	{
		$query = "CREATE TABLE `zc_partners` (`id` int(11) NOT NULL AUTO_INCREMENT, `text` text NOT NULL, `image` text NOT NULL, `sort` int(11) NOT NULL DEFAULT '99', UNIQUE KEY `id` (`id`) );";
		$sql = mysql_query($query);
		$folder = "../upload/zc_partners";
		if(!is_dir($folder))
		{
			mkdir($folder);
		}
	}
}

### Körs vid avinstallation
function zc_partners_uninstall()
{
	if(zc_partners_table_exists("zc_partners"))
	{
		$query = "DROP TABLE `zc_partners`";
		$sql = mysql_query($query);
		$folder = "../upload/zc_partners";
		if(is_dir($folder))
		{
			removeDir($folder);
		}
	}
}

### Körs vid aktivering
function zc_partners_activate()
{
	
}

### Körs vid avaktivering
function zc_partners_inactivate()
{
	
}

### Körs på varje sida på hemsidan när pluginet läses in
function zc_partners_init_page()
{
	
}

### Körs på varje sida i administrationspanelen när pluginet läses in
function zc_partners_init_admin()
{
	if(is_admin())
	{
		zc_add_menu("zc_partners_menu", "Partners");
		zc_add_menulink("zc_partners_menu", "Hantera partners", "plugin/zc_partners/");
		zc_add_menulink("zc_partners_menu", "Ny partner", "plugin/zc_partners/slide");
	}
}

### Körs när man går in på pluginet i adminpanelen (http://website.com/admin/plugin/yourplugin)
function zc_partners_show_adminpage()
{
	$page = "start";
	if(defined("OPT2"))
	{
		$page = safeText(OPT2);
	}
	rightsToSee(1);
	switch($page)
	{
		case "start":
			echo "<div class='pagetitle'>Hantera partners</div>";
			echo "<div class='description'>Dra de olika bilderna i den ordning du vill att de ska synas på hemsidan, uppifrån och ner.</div>";
			echo "<script src='" . PLUGINFOLDER . "script.js' type='text/javascript'></script>";
			echo '<ul id="zc_partners_sort" class="sortable_list">';
			// Tar fram listan från Databasen
			$result = mysql_query("SELECT * FROM zc_partners ORDER BY `sort` ASC") or die(mysql_error());
			while($row = mysql_fetch_array($result)) 
			{
				echo "<li id='zc_slide_" . $row['id'] . "'><img src='". HOST . "upload/zc_partners/cl-{$row['image']}' style='width:218px; float:left !important; cursor:ns-resize !important;' /><img src='". HOST . "admin/images/icons/pencil.png' alt='Editera bild' onclick='javascript: goto(\"". HOST . "admin/plugin/zc_partners/slide/". $row['id'] ."\");' style='border:0px;' /></li>";
			}
			echo "</ul>";
			break;
		case "slide":
			$imageid = "Ny partner";
			$text = "";
			$image = "";
			$oldImage = false;
			if(defined("OPT3"))
			{
				$s = safeText(OPT3);
				$s = (int)$s;
				$query = mysql_query("SELECT * FROM zc_partners WHERE id = {$s} LIMIT 1");
				if($query == false || mysql_num_rows($query) == 0)
				{
					adminerror(404);
				}
				$r = mysql_fetch_assoc($query);
				$imageid = $r['id'];
				$text = $r['text'];
				$image = $r['image'];
				$oldImage = true;
			}
				
			if(defined("OPT4"))
			{
				echo "<div class=\"messagebox\">" . safeText(OPT4) . "</div>";
			}
			echo "<script src='" . PLUGINFOLDER . "script.js' type='text/javascript'></script>";
			?>
			<form action="<?PHP host(); ?>admin/plugin/zc_partners/save" <?PHP if(!$oldImage){ echo 'enctype="multipart/form-data"'; } ?> method="post" id="customerform">
            <input type="hidden" id="imageid" name="imageid" value="<?PHP echo $imageid; ?>" />
            <table class="settingstable">
            	<?PHP
				if(!$oldImage)
				{
					?>
                <tr>
                    <td class="settingstitle">
                        Logotype
                    </td>
                    <td class="settingsfield">
                        <input tabindex="1" type="file" class="textfield" id="uplfile" name="uplfile" accept="image/jpeg" onchange='javascript: setChanged(customerform);' />
                    </td>
                </tr>
                <?PHP
				}
				else
				{
					?>
                    <img src="<?PHP host(); ?>upload/zc_partners/cl-<?PHP echo $image; ?>" style="border:1px solid #999; float:left; margin-bottom:10px;" />
                    <?PHP
				}
				?>
                <tr>
                    <td class="settingstitle">
                        Länk <span class="italic table-description">(Inklusive http://)</span>
                    </td>
                    <td class="settingsfield">
                        <input tabindex="1" type="text" class="textfield" id="text" name="text" onchange='javascript: setChanged(customerform);' value="<?PHP echo $text; ?>" style="width:400px;" />
                    </td>
                </tr>
            </table>
            <input tabindex="4" type="submit" value="Spara" class="submitbutton" style="cursor:pointer;" />
            <?PHP
            if($oldImage)
            {
				$_SESSION['return'] = HOST . "admin/plugin/zc_partners/";
                ?>
                <div class="hidden">
                	<div id="zc_slideshow_delete_partner_dialog" title="Säkerhetsfråga">Är du säker på att du vill ta bort den här partnern från hemsidan?</div>
                </div>
                <div class="button right" onclick='securityAskDeletePartner("<?PHP echo $imageid;?>")'>
                    Radera
                </div>
                <?PHP
            }
            ?>
            </form>
			<?PHP
			break;
		case "save":
			if(isset($_POST['imageid']))
			{
				$id = safeText($_POST['imageid']);					
				$text = safeText($_POST['text']);
				
				if($id == "")
				{
					adminerror("Något av de obligatoriska fälten är tomt!");
				}
				if($text == "")
				{
					$text = "#";
				}
				
				if($id == "Ny partner")
				{
					$upload = false;
					if (isset($_FILES['uplfile'])) 
					{
						// Mappen där filerna ska hamna
						$upload_dir = "../upload/zc_partners/";
						// De tillåtna filtyperna, separerade med komman, utan mellanrum
						$filetypes = 'jpg,gif,png';
						// Den största tillåtna storleken (20 MB)
						$maxsize = (1024*20000);
					
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
							adminerror('Du har en felaktig filtyp. Endast .jpg, .png och .gif är tillåtet!');
					
						// Generera unikt och scriptsäkert filnamn
						$bokstav = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6');
						$thefile = $_FILES['uplfile']['name'];
						while (file_exists($upload_dir.$thefile)) { $thefile = $bokstav[rand(0, count($bokstav)-1)].$thefile; }
						$thefile = getSlug($thefile);
						$thefile = substr($thefile, 0, strlen($thefile)-3);
						$thefile = $thefile . "." . $extension;
					
						// Flytta filen rätt
						if (is_uploaded_file($_FILES['uplfile']['tmp_name']) && move_uploaded_file($_FILES['uplfile']['tmp_name'],$upload_dir.$thefile)) {
							$upload = true;
							
							$imgsize = ImageSize($upload_dir.$thefile);
							if($imgsize['width'] > 175)
							{
								// Create a smaller image
								resizeImage($upload_dir.$thefile, 175, "cl-" . $thefile, $upload_dir, true);
								unlink($upload_dir.$thefile);
							}
							else
							{
								rename($upload_dir.$thefile, $upload_dir. "cl-" . $thefile);
							}
							
							makeBlackAndWhite($upload_dir. "cl-" . $thefile, $upload_dir. "bw-" . $thefile, $extension);
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
						$sql = mysql_query("INSERT INTO zc_partners (image,text) VALUES ('{$thefile}', '{$text}')");
						$query = mysql_query("SELECT * FROM zc_partners WHERE image = '{$thefile}' AND text = '{$text}'");
					}
					else
					{
						adminerror("Något gick fel vid uppladdningen, försök igen.");
					}
				}
				else
				{
					$sql = mysql_query("UPDATE zc_partners SET text = '{$text}' WHERE id = {$id}");
					$query = mysql_query("SELECT * FROM zc_partners WHERE id = {$id}");
				}
				$r = mysql_fetch_assoc($query);
				
				go(HOST . "admin/plugin/zc_partners/slide/" . $r['id'] . "/Sparat!");
			}
			break;
		case "delete":
			if(defined("OPT3"))
			{
				$id = (int)safeText(OPT3);
				$sqlq= mysql_query("SELECT * FROM zc_partners WHERE id = {$id}");
				$r = mysql_fetch_assoc($sqlq);
				$sql = mysql_query("DELETE FROM zc_partners WHERE id = {$id}");
				if(file_exists("../upload/zc_partners/cl-{$r['image']}"))
				{
					unlink("../upload/zc_partners/cl-{$r['image']}");
				}
				if(file_exists("../upload/zc_partners/bw-{$r['image']}"))
				{
					unlink("../upload/zc_partners/bw-{$r['image']}");
				}
			}
			$ret = $_SESSION['return'];
			unset($_SESSION['return']);
			if($ret == HOST . "admin/plugin/zc_partners/slide/")
			{
				$ret = HOST . "admin/plguin/zc_partners/";
			}
			go($ret);
			echo $ret;
			break;
		default:
			adminerror(404);
	}
}

function zc_partners_print($perline = 4)
{
	?>
	<table>
		<tr style="display:none;">
        <?PHP
		$sql = mysql_query("SELECT * FROM zc_partners ORDER BY sort");
		$i = 0;
		while($p = mysql_fetch_assoc($sql))
		{
			$name = str_replace(".", "", $p['image']);
			if($i%$perline == 0)
			echo "</tr><tr>";
			?>
			<td>
				<a href="<?PHP echo $p['text']; ?>" target="_blank" class="<?PHP echo $name; ?>link" /><img src="<?PHP host(); ?>upload/zc_partners/bw-<?PHP echo $p['image']; ?>" onmouseout="this.src = '<?PHP host(); ?>upload/zc_partners/bw-<?PHP echo $p['image']; ?>'" onmouseover="this.src = '<?PHP host(); ?>upload/zc_partners/cl-<?PHP echo $p['image']; ?>'" /></a>
			</td>
			<?PHP
			$i++;
		}
		?>
		</tr>
	</table>
	<?PHP
}

function zc_partners_print_preload()
{
	$sql = mysql_query("SELECT * FROM zc_partners");
	while($p = mysql_fetch_assoc($sql))
	{
		echo "<img src='". HOST ."upload/zc_partners/cl-{$p['image']}' />";
	}
}

function zc_partners_table_exists($tablename, $database = false)
{

    if(!$database) 
	{
        $res = mysql_query("SELECT DATABASE()");
        $database = mysql_result($res, 0);
    }

    $res = mysql_query("
        SELECT COUNT(*) AS count 
        FROM information_schema.tables 
        WHERE table_schema = '$database' 
        AND table_name = '$tablename'
    ");

    return mysql_result($res, 0) == 1;
}

?>