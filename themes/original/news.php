<?PHP
zc_page_top();

while(zc_have_news())
{
	?>
    <h3><?PHP zc_news_title(); ?></h3>
    <h5><?PHP zc_news_date(); ?></h5>
    <p><?PHP zc_news_content(); ?></p>
    <?PHP
}
zc_news_print_navigation();


zc_page_bottom();
?>