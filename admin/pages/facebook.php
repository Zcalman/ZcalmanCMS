<?PHP
require_once("facebook.php");

$app_id = "117421694936355";
$app_secret = "c258e43e9a1b03ea74699c9a6fdf6091";
$my_url = urlencode("http://localhost/New%20Project/admin/");
$token = "117421694936355|2.AQB0mECI1ivcve7v.3600.1310922000.0-586414643|dcKJyGVo2RdUi6D1aOIj5Xxe5Kg";

$facebook = new Facebook(array(
  'appId'  => $app_id,
  'secret' => $app_secret,
));

$user = $facebook->getUser();


$requestPermissionsUrl = $wallPostUrl = $facebook->getLoginUrl(array("scope" => "publish_stream"));

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

$url  = "https://www.facebook.com/dialog/oauth?client_id={$app_id}&redirect_uri={$my_url}&scope=manage_pages,publish_stream";
go($url);
//$d = json_decode(file_get_contents($url));
//$data = get_object_vars($d);
//print_r($data);
?>