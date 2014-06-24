<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

if (isset($_GET['page']) && $_GET['page'] == "logout")
{
	if(isset($_SESSION['is_online']))
	{
		if (isset($_SESSION['is_online'])) 
		{ 
			unset($_SESSION['is_online']);
		} 
		if (isset($_SESSION['username'])) 
		{ 
			unset($_SESSION['username']); 
		}
		if (isset($_SESSION['userclass'])) 
		{ 
			unset($_SESSION['userclass']); 
		}
		if (isset($_SESSION['email'])) 
		{ 
			unset($_SESSION['email']); 
		}
		if (isset($_SESSION['name'])) 
		{ 
			unset($_SESSION['name']); 
		}
	}		
	go(HOST.'admin/login/offline'); 
	exit(); 
}
	
?>