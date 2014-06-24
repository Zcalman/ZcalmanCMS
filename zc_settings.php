<?PHP
/**
*	Den här filen innehåller infomrtion om databas och annat som kan vara bra att veta
*	som inte går att hämta via databas.
**/

/* Databasinfo */
$db = "zcalman_cms";
$dbpass = "";
$dbuser = "root";
$dbhost = "localhost";

/* Localhostversion */
$localhostversion = true;
$localfolder = "ZcalmanCMS";

/* Sidans epost-adresser */
$server_mail = "server@zcalman.se";
$support_mail = "server@zcalman.se";

/* Inkluderar statiska variabler */
require("static.php");

/* Inkluderar versionsinfo */
require("version.php");

?>