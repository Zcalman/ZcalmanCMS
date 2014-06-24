<?PHP
zc_page_top();

while(zc_have_news())
{
	$month = wildheart_month(zc_get_news_date("n"));
	$day = zc_get_news_date("j");
	$year = zc_get_news_date("Y");
	?>
    <div class="newsObject">
        <h1><?PHP zc_news_title(); ?></h1>
        <h4><?PHP echo $day . " " . $month . " " . $year; ?></h4>
        <p><?PHP zc_news_content(); ?></p>
    </div>
    <div class="line"></div>
    <?PHP
}
zc_news_print_navigation();


zc_page_bottom();
?>