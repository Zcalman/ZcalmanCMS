<?PHP
zc_page_top();
?>
<div id="content">
<iframe src="<?PHP echo $pagevars['link']; ?>" scrolling="auto" id="iframepage" style="top:-<?PHP echo $pagevars['starty'];?>px; left:-<?PHP echo $pagevars['startx'];?>px;">
   <p>Din webbläsare stödjer inte iframes.</p>
</iframe>
</div>
<?PHP
zc_page_bottom();
?>