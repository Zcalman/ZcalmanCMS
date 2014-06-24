<?PHP
zc_page_top();

if(zc_have_article())
{
	?>
	<h1><?PHP zc_article_title(); ?></h1>
	<h5><?PHP zc_article_date(); ?></h5>
	<p><?PHP zc_article_content(); ?></p>
	<?PHP
}
zc_page_bottom();
?>