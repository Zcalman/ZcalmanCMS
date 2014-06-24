<?PHP
function run_update($version)
{
	global $upd_server;
	$folder = str_replace(".", "_", "" . $version);
	$upd_folder = $upd_server . $folder . "/";
	$upd_file_folder = $upd_folder . "files/";
	$upd_filelist = $upd_folder . "updlist.txt";
	$upd_error = 0;
	try
	{
		$ufile = fopen($upd_filelist, "r");
		$file_exist = true;
	}
	catch(Exception $e)
	{
		$file_exist = false;
		update_log($e->getMessage());
	}
	if($file_exist)
	{
		while(!feof($ufile))
		{
			$string = fgets($ufile);
			$parts = explode("=", $string,2);
			switch($parts[0])
			{
				case "newfile":
					update_log("Creating new file: " . trim($parts[1]));
				case "copy":
					$file = getUpdFileName(trim($parts[1]));
					try
					{
						copy($upd_file_folder . $file, "../../" . trim($parts[1]));
						update_log("Successful transfer of data to file: " . trim($parts[1]));
					}
					catch(Exception $e)
					{
						update_log($e->getMessage());
						$upd_error++;
					}
				break;
				case "newfolder":
					$folder = trim($parts[1]);
					update_log("Creating new folder: " . $folder);
					try
					{
						if(!is_dir("../../" . $folder))
						{
							mkdir("../../" . $folder);
							update_log("Folder created successfully: " . $folder);
						}
						else
						{
							update_log("Folder alread exists: " . $folder);
						}
					}
					catch(Exception $e)
					{
						update_log($e->getMessage());
						$upd_error++;
					}					
				break;
				case "delfile":
					$file = trim($parts[1]);
					update_log("Delete file: " . $file);
					try
					{
						if(file_exists("../../" . $file))
						{
							unlink("../../" . $file);
							update_log("File successfully deleted: " . $file);
						}
						else
						{
							update_log("Failed to delete file: ". $file);
						}
					}
					catch(Exception $e)
					{
						update_log($e->getMessage());
						$upd_error++;
					}
				break;
				case "delfolder":
					$folder = trim($parts[1]);
					update_log("Delete folder: " . $folder);
					try
					{
						removeDir("../../" . $folder);
						update_log("Folder successfully deleted: " . $folder);
					}
					catch(Exception $e)
					{
						update_log($e->getMessage());
						$upd_error++;
					}					
				break;
				case "sql":
					$sql = trim($parts[1]);
					update_log("Run SQL-query: \"{$sql}\"");
					try
					{
						mysql_query($sql);
						update_log("SQL-query succeeded");
					}
					catch(Exception $e)
					{
						update_log($e->getMessage());
						$upd_error++;
					}	
				break;
				default:
					update_log("Unknown command: {$parts[0]}");
					$upd_error++;
			}
		}
		if($upd_error == 0)
		{
			update_log("#######################################################");
			update_log(" Update to version {$version} succeeded!");
			update_log("#######################################################");
		}
		elseif($upd_error == 1)
		{
			update_log("#######################################################");
			update_log(" Update to version {$version} was done with {$upd_error} error!");
			update_log("#######################################################");
		}
		else
		{
			update_log("#######################################################");
			update_log(" Update to version {$version} was done with {$upd_error} errors!");
			update_log("#######################################################");
		}
		update_log("\r\n\r\n");
		fclose($ufile);
	}
	return $upd_error;
}

function update_log($message)
{
	global $upd_log_file;
	$ulf = fopen("../../" . $upd_log_file, "a");
	fwrite($ulf, $message . "\r\n");
	fclose($ulf);
}

function getUpdFileName($file)
{
	$temp = explode(".", $file);
	$ext_lenght = strlen($temp[count($temp) - 1]);
	return substr($file, 0, strlen($file) - $ext_lenght) . "upd";
}

?>