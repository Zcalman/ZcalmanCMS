<?PHP
function __autoload($className) { 
	if (file_exists('core/classes/'. $className . '.class.php'))
	{ 
		require_once('core/classes/' . $className . '.class.php'); 
		return true; 
	}
	else
	{
		echo "FATAL ERROR! Class {$className} dosen't exists!";
		return false;
	}
} 
?>