<?PHP
// Ett simpelt plugin

### Körs vid installation
function simpel_install()
{
	
}

### Körs vid avinstallation
function simpel_uninstall()
{
	
}

### Körs vid aktivering
function simpel_activate()
{
	
}

### Körs vid avaktivering
function simpel_inactivate()
{
	
}

### Körs på varje sida på hemsidan när pluginet läses in
function simpel_init_page()
{
	//zc_add_page("forum", "forum.php", "Forum");
}

### Körs på varje sida i administrationspanelen när pluginet läses in
function simpel_init_admin()
{
	//zc_add_menu("simpel", "Simpel Plugin");
	//zc_add_menulink("simpel", "Sök", "http://google.se");
}

### Körs när man går in på pluginet i adminpanelen (http://website.com/admin/plugin/yourplugin)
function simpel_show_adminpage()
{
	echo "<h2>Simpel Plugin</h2>";
}
?>