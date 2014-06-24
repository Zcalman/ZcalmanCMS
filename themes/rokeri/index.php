<?PHP
zc_page_top();
?>
<div id="content">
	<?PHP
	$sql = mysql_query("SELECT * FROM pages WHERE id = 1 LIMIT 1");
	$r = mysql_fetch_assoc($sql);
	if(mysql_num_rows($sql) == 1)
	{
		?>
		<h1><?PHP echo stripslashes($r['title']); ?></h1>
		<p><?PHP echo stripslashes($r['text']); ?></p>
		<?PHP
	}
	?>
</div>

<?PHP
zc_page_bottom();
?>