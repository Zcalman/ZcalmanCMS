<?PHP
// Awesome code goes here
date_default_timezone_set("Europe/Stockholm");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include("../../../core/core_functions.php");
include("../../../admin/admin_functions.php");
session_start();

rightsToSee(1,2);

$filename = date("YmdHis") . "_" . basename($_FILES['Filedata']['name']);
$folder;
if(isset($_GET['folder']))
{
	$folder = $_GET['folder'];
	$upload_dir = "../../../upload/zc_imagegallery/{$folder}/";
	if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $upload_dir.$filename))
	{
		$upload = true;
							
		$imgsize = ImageSize($upload_dir.$filename);
		if($imgsize['width'] > 800)
		{
			// Create a smaller image
			resizeImage($upload_dir.$filename, 800, "" . $filename, $upload_dir, true);
		}
	}
	else
	{
		$upload = false;
	}
}
?>