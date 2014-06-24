<?PHP
// Temats plugin

### Körs vid aktivering
function no_visual_activate()
{
	
}

### Körs vid avaktivering
function no_visual_inactivate()
{
	
}

### Körs på varje sida på hemsidan när pluginet läses in
function no_visual_init_page()
{
	
}

### Körs på varje sida i administrationspanelen när pluginet läses in
function no_visual_init_admin()
{
	if(isRights(1))
	{
		//zc_add_menulink("design_menu", "Temats inställningar", "themeplugin/");
	}
}

### Körs när man går in på pluginet i adminpanelen (http://website.com/admin/plugin/yourplugin)
function no_visual_show_adminpage()
{
	echo "<div class='pagetitle'>Temats inställningar</div>";
}
?>