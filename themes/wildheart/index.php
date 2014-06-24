<?PHP
zc_page_top();
?>

<?PHP
$sql = mysql_query("SELECT * FROM news ORDER BY id DESC LIMIT 3");
while($r = mysql_fetch_assoc($sql))
{
	$month = wildheart_month(splitToDate($r['date'], "n"));
	$day = splitToDate($r['date'], "j");
	$year = splitToDate($r['date'], "Y");
	?>
    <div class="newsObject">
        <h1><?PHP echo stripslashes($r['title']); ?></h1>
        <h4><?PHP echo $day . " " . $month . " " . $year; ?></h4>
        <p><?PHP echo stripslashes($r['text']); ?></p>
    </div>
    <div class="line"></div>
	<?PHP
}
?>
<div class="morePostsLink">
	<a href="<?PHP host(); ?>news" title="Fler inlägg">Fler inlägg</a>
</div>
<?PHP
zc_page_bottom();
?>