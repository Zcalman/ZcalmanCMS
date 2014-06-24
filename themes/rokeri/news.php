<?PHP
zc_page_top();
?>
<div id="content">
<?PHP
while(zc_have_news())
{
	?>
    <article>
    <h3><?PHP zc_news_title(); ?></h3>
    <h5><?PHP zc_news_date(); ?></h5>
    <p><?PHP zc_news_content(); ?></p><br />
    </article>
    <?PHP
}
zc_news_print_navigation();
?>
</div>
<?PHP
zc_page_bottom();
?>