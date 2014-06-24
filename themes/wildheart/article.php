<?PHP
zc_page_top();

if(zc_have_article())
{
	$month = wildheart_month(zc_get_article_date("n"));
	$day = zc_get_article_date("j");
	?>
    <div class="newsObject">
        <h1><?PHP zc_article_title(); ?></h1>
        <h4><?PHP echo $day . " " . $month; ?></h4>
        <p><?PHP zc_article_content(); ?></p>
    </div>
    <?PHP
}
zc_page_bottom();
?>