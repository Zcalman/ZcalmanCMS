<?PHP
function host()
{
	if(!defined("HOST"))
	{
		defineHOST();
	}
	echo HOST;
	
	return true;
}

function get_host()
{
	if(!defined("HOST"))
	{
		defineHOST();
	}
	
	return HOST;
}

function readOnly($int, $int2 = 0)
{
	if(!isRights($int) && !isRights($int2))
	{
		echo "readonly=\"readonly\"";
	}
}

function disabled($int = 0)
{
	if(isRights($int) || $int == 0)
	{
		echo "disabled=\"disabled\"";
	}
}
function setSel($a, $b)
{
	if(sameValue($a, $b))
	{
		echo "selected=\"selected\" ";
	}
}
function defineHOST()
{
	global $localhostversion;
	global $localfolder;
	$host = $_SERVER['HTTP_HOST']; 
	if($host == "localhost")
	{
		define("HOST", "http://localhost/" .$localfolder . "/");
	}
	elseif($localhostversion)
	{
		define("HOST", "http://".$_SERVER['HTTP_HOST']."/" .$localfolder . "/");
	}
	else
	{
		define("HOST", "http://".$_SERVER['HTTP_HOST']."/");
	}
	return true;
}

function go($url)
{
	echo "<script type=\"text/javascript\">goto(\"{$url}\");</script>";
	exit();
}

function defineOptAndThisPage($type = "page")
{
	global $page_title;
	// This page
	$this_page = HOST;
	$local_page = "";
	if($type == "admin")
	{
		$this_page = HOST . "admin/";
		$local_page .= "admin/";
	}
	if(isset($_GET['page']))
	{
		$this_page .= $_GET['page'] . "/";
		$local_page .= $_GET['page'] . "/";
	}
	// Definierar OPTIONS 1-7 
	if(isset($_GET['option1']))
	{
		define("OPT1", $_GET['option1']);
		$this_page .= OPT1 . "/";
		$local_page .= OPT1 . "/";
	}
	if(isset($_GET['option2']))
	{
		define("OPT2", $_GET['option2']);
		$this_page .= OPT2 . "/";
		$local_page .= OPT2 . "/";
	}
	if(isset($_GET['option3']))
	{
		define("OPT3", $_GET['option3']);
		$this_page .= OPT3 . "/";
		$local_page .= OPT3 . "/";
	}
	if(isset($_GET['option4']))
	{
		define("OPT4", $_GET['option4']);
		$this_page .= OPT4 . "/";
		$local_page .= OPT4 . "/";
	}
	if(isset($_GET['option5']))
	{
		define("OPT5", $_GET['option5']);
		$this_page .= OPT5 . "/";
		$local_page .= OPT5 . "/";
	}
	if(isset($_GET['option6']))
	{
		define("OPT6", $_GET['option6']);
		$this_page .= OPT6 . "/";
		$local_page .= OPT6 . "/";
	}
	if(isset($_GET['option7']))
	{
		define("OPT7", $_GET['option7']);
		$this_page .= OPT7 . "/";
		$local_page .= OPT7 . "/";
	}
	define("THIS_PAGE", $this_page);
	define("LOCAL_PAGE", $local_page);
	$page_title = get_setting("pagetitle");
	$page_description = get_setting("pagedescription");
	define("PAGE_TITLE", $page_title);
	define("PAGE_DESCRIPTION", $page_description);
	
}
function defineLanguage()
{
	$lang = get_setting("language");
	define("LANGUAGE", $lang);
}
function defineTheme()
{
	$detect = new Mobile_Detect();
	$portable_device_version = true;
	$portable_device = false;
	if($detect->isMobile() || $detect->isTablet())
	{
		$portable_device = true;
	}
	
	if(isset($_SESSION['use_mobile_version']))
	{
		$portable_device_version = $_SESSION['use_mobile_version'];
	}
	else
	{
		$_SESSION['use_mobile_version'] = true;
	}
	$use_mobile_theme = get_setting("use_mobile_theme");
	$name = get_setting("theme");
	$_SESSION['using_mobile_version'] = false;
	if(is_dir("themes/" . $name ."_mobile") && $use_mobile_theme == "true" && $portable_device == true && $portable_device_version == true)
	{
		$name = $name . "_mobile";
		$_SESSION['using_mobile_version'] = true;
	}
	$url = get_host() . "themes/" . $name ."/";
	$folder = "themes/" . $name ."/";
	define("THEME", $name);
	define("THEME_FOLDER", $folder);
	define("THEME_URL", $url);
}
function checkForMobile()
{
	$detect = new Mobile_Detect();
	$use_mobile_theme = get_setting("use_mobile_theme");
	if(($detect->isMobile() || $detect->isTablet()) && isset($_SESSION['use_mobile_version']) && $_SESSION['use_mobile_version'] == true && $_SESSION['using_mobile_version'] == true)
	{
		if(!isset($_COOKIE['mobile_message_shown']) && !isset($_SESSION['mobile_message_shown']))
		{
			$portable_device_text = get_setting("portable_device_text");
			?>
			<script type="text/javascript">
			$(document).ready(function() {
				var t = setTimeout("showMobilePopup()", 2000);
			});
			function showMobilePopup()
			{	
				alert("<?PHP echo $portable_device_text; ?>");
			}
			</script>
			<?PHP
			$_SESSION['mobile_message_shown'] = true;
		}
	}
	
}
function loadPage()
{
	global $content;
	require($content);
}
function error($nr)
{
	go(HOST. "error/". $nr);
	exit();
	die();
}
function code_error($nr)
{
	header("Location: " .HOST. "error/". $nr);
	exit();
	die();
}
function get_errorpage($nr)
{
	global $pagevars;
  	$pagevars['title'] = "Felkod {$nr}";
	$errorfile = $nr . ".php";
	if(file_exists(THEME_FOLDER . $errorfile))
	{
		return(THEME_FOLDER . $errorfile);
	}
	else
	{
		return "core/error/" . $errorfile;
	}
}
function is_admin()
{
	if(is_user() && isset($_SESSION['userclass']) && $_SESSION['userclass'] == 1)
		return true;
	else
		return false;
}
function is_writer()
{
	if(is_user() && isset($_SESSION['userclass']) && $_SESSION['userclass'] == 2)
		return true;
	else
		return false;
}
function is_user()
{
	if(isset($_SESSION['is_online']) && $_SESSION['is_online'])
		return true;
	else
		return false;
}
function splitToDate($dateString, $format)
{
	if(strlen($dateString) < 8)
	{
		return false;
	}
	else
	{
		$year = (int)substr($dateString, 0, 4);
		$month = (int)substr($dateString, 4, 2);
		$day = (int)substr($dateString, 6, 2);
		$hour = 0;
		$min = 0;
		$sec = 0;
		if(strlen($dateString) > 8)
		{
			$hour = substr($dateString, 8, 2);
			$min = substr($dateString, 10, 2);
		}
		if(strlen($dateString) > 12)
		{
			$sec = substr($dateString, 12, 2);
		}
		return date($format, mktime($hour, $min, $sec, $month, $day,$year));
	}
}
function cutText($text, $length, $nicecut = true)
{
	$temptext = $text;
	if(strlen($temptext) > $length)
	{
		$temptext = substr($temptext, 0, $length);
		if($nicecut)
		{
			$textend = strrpos($temptext, ' ');
			$temptext = substr($temptext, 0, $textend);
		}
		$temptext .= "...";
	}
	
	return $temptext;
}
function sameValue($a, $b)
{
	if($a == $b)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function safeText($string, $html = false)
{
	$temp = $string;
	if(!$html)
	{
		$temp = strip_tags($temp);
	}
	$temp = stripslashes($temp);
	$temp = addslashes($temp);
	$temp = mysql_real_escape_string($temp);
	$temp = trim($temp);
	return $temp;
}
function settingSafeText($string, $html = false) 
{
	$temp = safeText($string, $html);
	$temp = str_replace("|", "&#124;", $temp);
	return $temp;
}
function stripHtml($string)
{
	$temp = strip_tags($string);
	return $temp;
}
function fixSwe($string)
{
	$temp = $string;
	$temp = str_replace("å" , "&aring;", $temp);
	$temp = str_replace("ä" , "&auml;", $temp);
	$temp = str_replace("ö" , "&ouml;", $temp);
	$temp = str_replace("Å" , "&Aring;", $temp);
	$temp = str_replace("Ä" , "&Auml;", $temp);
	$temp = str_replace("Ö" , "&Ouml;", $temp);
	return $temp;
}
function nlBr($string, $replace = "<br />")
{
	return str_replace(array("\r\n", "\r", "\n"), $replace, $string);
}
function nlMailBr($string)
{
	return str_replace(array("\r\n", "\r", "\n"), "%0A", $string);
}
function dayName($month, $day, $year)
{
	global $dayName;
	$d = date("D", mktime(0,0,0,$month,$day,$year));
	return $dayName[$d];
}
function monthName($month)
{
	global $monthName;
	return $monthName[$month];
}
function today($format)
{
	return date($format);	
}
function fixDate($day, $month, $year)
{
	return date("d-m-Y", mktime(0,0,0,$month,$day,$year));
}
function g($label)
{
	// return getvalue if there is one
	if(isset($_GET[$label]))
	{
		return $_GET[$label];
	}
	else
	{
		return false;
	}
}
function p($label)
{
	// return getvalue if there is one
	if(isset($_POST[$label]))
	{
		return $_POST[$label];
	}
	else
	{
		return false;
	}
}
function get_setting($name)
{
	$query = mysql_query("SELECT * FROM settings WHERE `name` = '{$name}'");
	if(mysql_num_rows($query) == 1)
	{
		$r = mysql_fetch_assoc($query);
		return $r['value'];
	}
	else
	{
		return "No such setting in database";
	}
}
function set_setting($name, $value)
{
	$query = mysql_query("SELECT * FROM settings WHERE `name` = '{$name}'");
	if(mysql_num_rows($query) == 1)
	{
		$sql = mysql_query("UPDATE settings SET `value` = '{$value}' WHERE `name` = '{$name}' LIMIT 1");
	}
	else
	{
		$sql = mysql_query("INSERT INTO settings (name,value) VALUES ('{$name}','{$value}')");
	}
}
function generateString($length)
{
	$chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
	$numChars = count($chars);
	$return = "";
	for($i = 0; $i < $length; $i++)
	{
		$return .= $chars[rand(0, $numChars-1)];
	}
	
	return $return;
}
function getSlug($text)
{
	$ret = "";
	$chars = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "-", "_", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
	$slug = $text;
	$slug = str_replace("å", "a", $slug);
	$slug = str_replace("Å", "a", $slug);
	$slug = str_replace("ä", "a", $slug);
	$slug = str_replace("Ä", "a", $slug);
	$slug = str_replace("ö", "o", $slug);
	$slug = str_replace("Ö", "o", $slug);
	$slug = str_replace("Ã¥", "a", $slug);
	$slug = str_replace("Ã¤", "a", $slug);
	$slug = str_replace("Ã¶", "o", $slug);
	$slug = str_replace("Ã…", "a", $slug);
	$slug = str_replace("Ã„", "a", $slug);
	$slug = str_replace("Ã–", "o", $slug);
	$slug = str_replace("&aring;", "a", $slug);
	$slug = str_replace("&auml;", "a", $slug);
	$slug = str_replace("&ouml;", "o", $slug);
	$slug = str_replace(" ", "-", $slug);
	$texts = str_split(strtolower($slug));
	foreach($texts as $c)
	{
		if(in_array($c, $chars))
		{
			$ret .= $c;
		}
	}
	return $ret;
}
function ImageSize($file)
{
	list($width, $height) = getimagesize($file);
	$arr = array("width" => $width, "height" => $height);
	return $arr;
}
function resizeImage($file, $new_width, $filename, $path, $cubic = false)
{
	
	// Set the new filepath
	$new_file = $path . $filename;
	
	// Filetype
	$ext = substr($file, strrpos($file, ".")+1);
	
	// Get size of image
	list($width, $height) = getimagesize($file);
	
	// Set the new size
	if($height > $width && $cubic)
	{
		$new_height = $new_width;
		$new_width = ($width / $height) * $new_height;
	}
	else
	{
		$new_height = ($height/$width) * $new_width;
	}
	
	// Create an image of the file
	switch(strtolower($ext))
	{
		case "png":
			$image = imagecreatefrompng($file);
			break;
		case "jpeg":
			$image = imagecreatefromjpeg($file);
			break;
		case "jpg":
			$image = imagecreatefromjpeg($file);
			break;
		case "gif":
			$image = imagecreatefromgif($file);
			break;
		default:
			exit("<strong>FATAL ERROR!</strong><br />File is not a valid image.");
	}

	// Create the new image
	$temp_image = imagecreatetruecolor($new_width, $new_height);
	
	// Set a White & Transparent Background Color
	$bg = imagecolorallocatealpha($temp_image, 255, 255, 255, 127);
	imagefill($temp_image, 0, 0 , $bg);
	
	// Fix alpha
	imagesavealpha($image, true);
	
	// Copy the image in to the new file
	imagecopyresampled($temp_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	
	//$temp_image = imagerotate($temp_image, 180, 0);
	
	// Fix alpha
    imagealphablending($temp_image,false);
	imagesavealpha($temp_image, true);
	
	// Save the new image
	switch(strtolower($ext))
	{
		case "png":
			imagepng($temp_image, $new_file, 9);
			break;
		case "jpeg":
			imagejpeg($temp_image, $new_file, 100);
			break;
		case "jpg":
			imagejpeg($temp_image, $new_file, 100);
			break;
		case "gif":
			imagegif($temp_image, $new_file);
			break;
		default:
			exit("<strong>FATAL ERROR!</strong><br />File is not an image.");
	}
	
	// Destroy the images to save memory
	imagedestroy($image);
	imagedestroy($temp_image);
}
function makeBlackAndWhite($file, $new_file, $type)
{
	// Get the dimensions
	list($width, $height) = getimagesize($file); 
	
	// Define our source image
	switch(strtolower($type))
	{
		case "png":
			$source = imagecreatefrompng($file);
			break;
		case "jpeg":
			$source = imagecreatefromjpeg($file); 
			break;
		case "jpg":
			$source = imagecreatefromjpeg($file); 
			break;
		case "gif":
			$source = imagecreatefromgif($file); 
			break;
		default:
			exit("<strong>FATAL ERROR!</strong><br />File is not an image.");
	}
	
	// Creating the Canvas 
	$bwimage= imagecreate($width, $height); 
	
	//Creates the 256 color palette
	for ($c=0;$c<256;$c++) 
	{
		$palette[$c] = imagecolorallocate($bwimage,$c,$c,$c);
	}
	
	//Reads the origonal colors pixel by pixel 
	for ($y=0;$y<$height;$y++) 
	{
		for ($x=0;$x<$width;$x++) 
		{
			$rgb = imagecolorat($source,$x,$y);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			
			//This is where we actually use yiq to modify our rbg values, and then convert them to our grayscale palette
			$gs = yiq($r,$g,$b);
			imagesetpixel($bwimage,$x,$y,$palette[$gs]);
		}
	}
	
	// Spara den nya bilden
	switch(strtolower($type))
	{
		case "png":
			// Fix alpha
			imagealphablending($bwimage,false);
			imagesavealpha($bwimage, true);
			imagepng($bwimage, $new_file, 0);
			break;
		case "jpeg":
			imagejpeg($bwimage, $new_file, 100);
			break;
		case "jpg":
			imagejpeg($bwimage, $new_file, 100);
			break;
		case "gif":
			imagegif($bwimage, $new_file);
			break;
		default:
			exit("<strong>FATAL ERROR!</strong><br />File is not an image.");
	}
}
//Används i svartvit-funktionen
function yiq($r,$g,$b) 
{
	return (($r*0.299)+($g*0.587)+($b*0.114));
}
function getFileIcon($ext)
{
	global $fileicons;
	if(array_key_exists($ext, $fileicons))
	{
		$image = $fileicons[$ext];
	}
	else
	{
		$image = "page_white.png";
	}
	return HOST . "admin/images/icons/filetypes/" . $image;
}
function getFileSize($bytes, $precision = 2)
{
    $unit = array('B','KB','MB','GB','TB','PB','EB');

    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
}
function pLoadPluginInfo($slug)
{
	$plugininfofile = "plugins/{$slug}/plugin_info.php";
	$pluginfile = "plugins/{$slug}/plugin.php";
	if(file_exists($plugininfofile) && file_exists($pluginfile))
	{
		include($plugininfofile);
		if($plugin_slug != $slug)
		{
			return false;							// Kontroll så slug är samma som mappnamnet.
		}
		return array("slug" => $plugin_slug, "name" => $plugin_name, "version" => $plugin_version, "creator" => $plugin_creator, "url" => $plugin_url, "description" => $plugin_desc);
	}
	else
	return false;
}
function loadPlugins($type)
{
	global $pluginmeta;
	
	$query = mysql_query("SELECT * FROM plugins WHERE `active` = 1 AND `installed` = 1");
	while($r = mysql_fetch_assoc($query))
	{
		$pluginmeta = pLoadPluginInfo($r['slug']);
		$pluginmeta['type'] = "plugin";
		$pluginfile = "plugins/{$r['slug']}/plugin.php";
		if($type == "admin")
		{
			$pluginfile = "../" . $pluginfile;
		}
		$loadpluginfunction = $r['slug'] . "_init_" . $type;
		if(file_exists($pluginfile))
		{
			include($pluginfile);
			if(function_exists($loadpluginfunction))
			{
				$loadpluginfunction();
			}
		}
		$pluginmeta = NULL;
	}
}
function loadPlugin($slug, $type, $loadfile = true, $plugintype = "plugin")
{
	// Används i admin/pages/pluginactions.php och admin/pages/save.php
	$pluginfile = "../plugins/{$slug}/plugin.php";
	$pluginfolder = HOST . "plugins/{$slug}/";
	if($plugintype == "theme")
	{
		$pluginfile = "../themes/{$slug}/plugin.php";
		$pluginfolder = HOST . "themes/{$slug}/";
	}
	define("PLUGINFOLDER", $pluginfolder);
	$loadpluginfunction = $slug . "_" . $type;
	if(file_exists($pluginfile))
	{
		if($loadfile)
		{
			include($pluginfile);
		}
		if(function_exists($loadpluginfunction))
		{
			$loadpluginfunction();
			return true;
		}
		return true;
	}
	else
	{
		return false;
	}
}
function loadThemePlugin($type)
{
	global $pluginmeta;
	$theme = get_setting("theme");
	if($type == "admin")
	{
		$pluginmeta = readTheme($theme);
	}
	else
	{
		$pluginmeta = readTheme($theme, false);
	}
	$pluginmeta['slug'] = $theme;
	$pluginmeta['type'] = "theme";
	if($pluginmeta['plugin'])
	{
		$pluginfile = "themes/".$theme."/plugin.php";
		if($type == "admin")
		{
			$pluginfile = "../" . $pluginfile;
		}
		$loadpluginfunction = $theme . "_init_" . $type;
		if(file_exists($pluginfile))
		{
			include($pluginfile);
			if(function_exists($loadpluginfunction))
			{
				$loadpluginfunction();
			}
		}
	}
}

function readTheme($folder, $admin = true)
{
	$dir = "../themes/" . $folder;
	if(!$admin)
	{
		$dir = "themes/" . $folder;
	}
	$settingsfile = $dir . "/settings.php";
	if(file_exists($settingsfile))
	{
		include($settingsfile);
		return $themeinfo;
	}
	else
	{
		return false;
	}
}
function hasPlugin($pluginslug)
{
	$query = mysql_query("SELECT `id` FROM `plugins` WHERE `active` = 1 AND `installed` = 1 AND `slug` = '{$pluginslug}'");
	if(mysql_num_rows($query) == 1) {
		return true;
	}
	else {
		return false;
	}
}

?>
