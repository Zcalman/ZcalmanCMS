<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}

rightsToSee(1);
if (defined("OPT1"))
{ 
	$pagenr = mysql_real_escape_string(trim(OPT1)); 
} 
else
{
	$pagenr = 1;
}
if (defined("OPT2"))
{ 
	$temp = mysql_real_escape_string(trim(OPT2)); 
	if($temp == "namedown")
	{
		$order = "name ASC";
		$afterlink = "namedown";
	}
	elseif($temp == "nameup")
	{
		$order = "name DESC";
		$afterlink = "nameup";
	}
	elseif($temp == "nickdown")
	{
		$order = "username ASC";
		$afterlink = "nickdown";
	}
	elseif($temp == "nickup")
	{
		$order = "username DESC";
		$afterlink = "nickup";
	}
	elseif($temp == "userid")
	{
		$order = "id";
		$afterlink = "userid";
	}
	else
	{
		$order = "id";
		$afterlink = "none";
	}
} 
else
{
	$order = "id";
	$afterlink = "none";
}
if (defined("OPT3"))
{ 
	$temp = mysql_real_escape_string(trim(OPT3)); 
	if($temp == "users")
	{
		$afterlink2 = "users";
		$select = "WHERE userclass = 2";
	}
	elseif($temp == "admins")
	{
		$afterlink2 = "admins";
		$select = "WHERE userclass = 1";
	}
	else
	{
		$afterlink2 = "all";
		$select = "";
	}
} 
else
{
	$select = "";
	$afterlink2 = "all";
}
$addlink = $afterlink . "/" . $afterlink2;
$pageNav = new PageNav(15, 10, $pagenr, "userbase", "goto", HOST . "admin/users/", "/{$addlink}", "{$select} ORDER BY {$order}");
$works = new Table(4, "ID|Användarnamn|E-postadress|Namn", "50|170|350|220", "clickUser");
while($r = mysql_fetch_assoc($pageNav->sqlres))
{
	$works->addRow($r['id'], "{$r['id']}|{$r['username']}|{$r['email']}|{$r['name']}");
}
?>
<div class="pagetitle">
Användare
</div>
<div class="searchOrder" style="margin-bottom:20px;">
    <div class="dropbox">
        <select name="orderlist" class="dropdown" id="orderlist">
            <option <?PHP setSel($afterlink, "none"); ?>value="none">Sortera efter...</option>
            <option <?PHP setSel($afterlink, "userid"); ?>value="userid">Id</option>
            <option <?PHP setSel($afterlink, "namedown"); ?>value="namedown">Namn A-Ö</option>
            <option <?PHP setSel($afterlink, "nameup"); ?>value="nameup">Namn Ö-A</option>
            <option <?PHP setSel($afterlink, "nickdown"); ?>value="nickdown">Användarnamn A-Ö</option>
            <option <?PHP setSel($afterlink, "nickup"); ?>value="nickup">Användarnamn Ö-A</option>
        </select>
    </div>
    <div class="dropbox" style="margin-left:20px;">
        <select name="showonlylist" class="dropdown" id="showonlylist">
            <option <?PHP setSel($afterlink2, "all"); ?>value="all">Visa alla</option>
            <option <?PHP setSel($afterlink2, "users"); ?>value="users"><?PHP echo $writerclassname; ?></option>
            <option <?PHP setSel($afterlink2, "admins"); ?>value="admins"><?PHP echo $administratorclassname; ?></option>
        </select>
    </div>
</div>
<div class="button right" onclick='javascript: goto(host + "admin/user");'>
    Ny användare
</div>
<?PHP
$works->printTable();
echo "\n";
$pageNav->printNav();
?>