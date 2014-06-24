<ul id="menu">
	<?PHP 
	$sql = mysql_query("SELECT * FROM menu WHERE parent = 0 ORDER BY `sort` ASC");
	while($r = mysql_fetch_assoc($sql))
	{
		$query = mysql_query("SELECT * FROM menu WHERE parent = {$r['id']} ORDER BY `sort` ASC");
		?>
	<li><a href="<?PHP echo $r['link']; ?>" target="<?PHP echo $r['target']; ?>" title="<?PHP echo $r['title']; ?>"><?PHP echo $r['title']; ?></a>
    	<?PHP
		if(mysql_num_rows($query) > 0 && $r['submenu'] == 1)
		{
			?>
    	<ul>
        	<?PHP
			while($s = mysql_fetch_assoc($query))
			{
				?>
        	<li><a href="<?PHP echo $s['link']; ?>" title="<?PHP echo $s['title']; ?>" target="<?PHP echo $s['target']; ?>"><?PHP echo $s['title']; ?></a></li>
            	<?PHP
			}
			?>
        </ul>
        	<?PHP
		}
		?>
    </li>
    	<?PHP
	}
	?>
</ul>
    