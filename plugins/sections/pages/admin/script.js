// Script
function clickSection(id)
{
	goto(host + "admin/plugin/sections/section/" + id);
}
$(document).ready(function() {
	$('#order-list').change(function() {
		var tempval = $('#showonlylist').val();
		goto(adminhost + "plugin/sections/start/1/" + this.value + "/" + tempval);
	});
	$('#order-pages-list').change(function() {
		var tempval = $('#showonlylist').val();
		var sect = $('#sectionid').val();
		goto(adminhost + "plugin/sections/sectionpages/" + sect + "/1/" + this.value + "/" + tempval);
	});
	$('#order-respages-list').change(function() {
		var tempval = $('#showonlylist').val();
		var sect = $('#sectionid').val();
		goto(adminhost + "plugin/sections/sectionrespages/" + sect + "/1/" + this.value + "/" + tempval);
	});
	$('#order-news-list').change(function() {
		var tempval = $('#showonlylist').val();
		var sect = $('#sectionid').val();
		goto(adminhost + "plugin/sections/sectionnews/" + sect + "/1/" + this.value + "/" + tempval);
	});
	$('#select_section_submit').click(function() {
		var tempval = $('#select_section').val();
		var page = $('#select_section_page').val();
		goto(host + "admin/plugin/sections/" + page + "/" + tempval);
	});
	$('#new_submenulink').click(function() {
		$('#new_sectionssubmenulink_dialog').dialog({modal: true, height:265, width:470, resizable: false, buttons: { "Spara": function() { if(validateSubMenu(new_submenulink_form)) { new_submenulink_form.submit();} }, "Avbryt": function() { $(this).dialog("close"); } } });
	});
});

function getSectionSlug(string, id)
{
	attach_file(host + "plugins/sections/pages/admin/getSectionSlug.php?id=" + id + "&string=" + string);
}
function getSectionPageSlug(string, id)
{
	var sect = $('#sectionid').val();
	attach_file(host + "plugins/sections/pages/admin/getPageSlug.php?id=" + id + "&sid=" + sect + "&string=" + string);
}
function getSectionNewsSlug(string, id)
{
	var sect = $('#sectionid').val();
	attach_file(host + "plugins/sections/pages/admin/getNewsSlug.php?id=" + id + "&sid=" + sect + "&string=" + string);
}

function clickSectionPage(id)
{
	var sect = $('#sectionid').val();
	goto(host + "admin/plugin/sections/sectionpage/" + sect + "/" + id);
}

function clickSectionNews(id)
{
	var sect = $('#sectionid').val();
	goto(host + "admin/plugin/sections/sectionnew/" + sect + "/" + id);
}

function clickSectionResPage(id)
{
	var sect = $('#sectionid').val();
	goto(host + "admin/plugin/sections/sectionrespage/" + sect + "/" + id);
}

function clickSectionUser(id)
{
	var sect = $('#sectionid').val();
}
function sections_delete_rights(id,name,sectionid)
{
	$('#delete_right_dialog #delete_rightsname').html(name);
	$('#delete_right_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/plugin/sections/delete/right/" + id + "/" + sectionid); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function sections_new_right()
{
	var user = $('#user').val();
	var form = document.getElementById('new_right_form');
	$('#new_right_dialog').dialog({modal: true, height:265, width:470, resizable: false, buttons: { "Spara": function() { form.submit(); }, "Avbryt": function() { $(this).dialog("close"); } } });	
}

function validateSectionform(form, standard)
{
	var ret = true;
	var text = ""
	var errors = 0;
	if(form.title.value == "" || form.title.value == " " || form.title.value == "" + standard)
	{
		text += " - Namn är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	if(form.slug.value == "" || form.slug.value == " ")
	{
		text += " - Länken är felaktig.\n";
		ret = false;
		errors++;
	}
	
	if(errors != 0)
	{
		alert(errors + " fel hittades\n\n" + text);
	}
	else if(oldSlug != form.slug.value && form.pageid.value != "Ny sektion")
	{
		if(!confirm("VARNING!\n\nDu håller på att ändra länken på en befintlig sektion. Detta kan vilseleda besökare då länkar från andra hemsidor blir inaktiva. Alla länkar i menyn på sidan uppdateras automatiskt.\nVill du återställa länken till det normala, klicka på avbryt.\n\nÄr du säker på att du vill fortsätta?"))
		{
			ret = false;
			customerform.slug.value = oldSlug;
			var targetDiv = document.getElementById("pagelink");
			targetDiv.innerHTML = "Länk: "+ host + oldSlug + " <a href='#' id='edit_slug_link' onclick='javascript: editSlug();'>Ändra</a>";
		}
	}
	return ret;
}
function editSectionSlug()
{
	edit_slug_form.slugtext.value = customerform.slug.value;
	$('#edit_slug_dialog').dialog({modal: true, resizable: false, height: 240, width:450, buttons: { "Ändra": function() { getSectionSlug(edit_slug_form.slugtext.value, pageid); $(this).dialog("close"); }, "Avbryt": function() { $(this).dialog("close"); } } });	
}
function editPageSlug()
{
	edit_slug_form.slugtext.value = customerform.slug.value;
	$('#edit_slug_dialog').dialog({modal: true, resizable: false, height: 240, width:450, buttons: { "Ändra": function() { getSectionPageSlug(edit_slug_form.slugtext.value, pageid); $(this).dialog("close"); }, "Avbryt": function() { $(this).dialog("close"); } } });	
}
function editNewsSlug()
{
	edit_slug_form.slugtext.value = customerform.slug.value;
	$('#edit_slug_dialog').dialog({modal: true, resizable: false, height: 240, width:450, buttons: { "Ändra": function() { getSectionNewsSlug(edit_slug_form.slugtext.value, newsid); $(this).dialog("close"); }, "Avbryt": function() { $(this).dialog("close"); } } });	
}

function securityAskDeleteSection(name,id)
{
	$('#delete_section_dialog #delete_sectionname').html(name);
	$('#delete_section_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/plugin/sections/delete/section/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}

function securityAskDeleteSectionNews(name,id)
{
	$('#delete_news_dialog #delete_newstitle').html(name);
	$('#delete_news_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/plugin/sections/delete/news/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}

function securityAskDeleteSectionPage(name,id)
{
	$('#delete_page_dialog #delete_pagetitle').html(name);
	$('#delete_page_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/plugin/sections/delete/page/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function securityAskDeleteSectionsMenu(name,id)
{
	$('#delete_sectionsmenu_dialog #delete_sectionsmenutitle').html(name);
	$('#delete_sectionsmenu_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/plugin/sections/delete/menu/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function securityAskDeleteSectionsSubMenu(name,id)
{
	$('#delete_sectionssubmenu_dialog #delete_sectionssubmenutitle').html(name);
	$('#delete_sectionssubmenu_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/plugin/sections/delete/menu/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}

$(document).ready(
	function() {
		$("#sectionmenu_sort").sortable({
			axis: 'y',
			update : function () {
				startLoad("Sparar");
				serial = $('#sectionmenu_sort').sortable('serialize');
				$.ajax({
					url: host + "plugins/sections/pages/admin/sortmenu.php",
					type: "post",
					data: serial,
					error: function(){
						alert("Theres an error with AJAX");
					}
				});
			}
		});
	}
);