function falt(obj,val,org)
{
	if(val == org)
	{
		obj.value = '';
	}
}

function tom(obj,val,org)
{
	if(val == '')
	{
		obj.value = org;
	}
}
// När man klickar på en länk anropas funktionen goto
function goto(to)
{
	if(changed != false)
	{
		if(confirmLeave(changed, to))
		{
			top.location.href = to;
		}
	}
	else
	{
		top.location.href = to;
	}
}
function gotonew(to, name)
{
	if(changed != false)
	{
		if(confirmLeave(changed, to))
		{
			window.open(to, "Nytt fönster",'width=990,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,copyhistory=yes,resizable=yes');
		}
	}
	else
	{
		window.open(to, name, 'width=990,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,copyhistory=yes,resizable=yes');
	}
}
function confirmLeave(varname, to)
{
	ret = false;
	$(document).ready(function() {
		$('#confirmLeaveDialog').dialog({modal: true, width: 350, resizable: false, buttons: { "Fortsätt utan att spara": function() { changed = false; $(this).dialog("close"); goto(to); }, "Avbryt": function() { $(this).dialog("close"); } } });
	});
	return ret;
}
function logout()
{
	goto(host + "admin/logout");
}
function isset(varname) 
{
	if(typeof( window[ varname ] ) != "undefined") 
	{
		return true;
	}
	else
	{
		return false;
	}
}
function setChanged(form)
{
	changed = form;
}
function setSettingsChanged(form, field)
{
	changed = form;
	var fields = form.changedfields.value;
	fields += field + ",";
	form.changedfields.value = fields;
}
function showDialog(dialog)
{
	id = "#" + dialog;
	$(id).dialog({modal: true, resizable: false, buttons: { "Stäng": function() { $(this).dialog("close"); } } });
}
$(document).ready(function() {
	// Klick på logga ut
	$('#logoutbutton').click(function() {
		$('#logoutdialog').dialog({modal: true, resizable: false, buttons: { "Ok": function() { logout(); }, "Avbryt": function() { $(this).dialog("close"); } } });
	});
	$('#new_submenulink').click(function() {
		$('#new_submenulink_dialog').dialog({modal: true, height:265, width:470, resizable: false, buttons: { "Spara": function() { if(validateSubMenu(new_submenulink_form)) { new_submenulink_form.submit();} }, "Avbryt": function() { $(this).dialog("close"); } } });
	});
	$('.hide_show_customerinfo .button').click(function() {
		if($('#customerinfo').is(':hidden'))
		{
			$('#customerinfo').show();
		}
		else
		{
			$('#customerinfo').hide();
		}
	});
	$('.hide_show_userpassword .button').click(function() {
		if($('#customerinfo').is(':hidden'))
		{
			$('#customerinfo').show();
		}
		else
		{
			$('#customerinfo').hide();
		}
	});
	$('#orderlist').change(function() {
		var tempval = $('#showonlylist').val();
		goto(adminhost + page + "/1/" + this.value + "/" + tempval);
	});
	$('#showonlylist').change(function() {
		var tempval = $('#orderlist').val();
		goto(adminhost + page + "/1/" + tempval + "/" + this.value);
	});
	$('#submenu').change(function() {
		var tempval = this.value;
		if(tempval == 1)
		{
			$('#submenufield').show();
		}
		else
		{
			$('#submenufield').hide();
		}
	});

});
function securityAskDeletePage(name,id)
{
	$('#delete_page_dialog #delete_pagetitle').html(name);
	$('#delete_page_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/delete/page/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function securityAskDeleteNews(name,id)
{
	$('#delete_news_dialog #delete_newstitle').html(name);
	$('#delete_news_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/delete/news/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function securityAskDeleteMenu(name,id)
{
	$('#delete_menu_dialog #delete_menutitle').html(name);
	$('#delete_menu_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/delete/menu/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function securityAskDeleteSubMenu(name,id)
{
	$('#delete_submenu_dialog #delete_submenutitle').html(name);
	$('#delete_submenu_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/delete/menu/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function securityAskDeleteUser(name,id)
{
	$('#delete_user_dialog #delete_username').html(name);
	$('#delete_user_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/delete/user/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function securityAskDeleteTheme(name,id)
{
	$('#delete_theme_dialog #delete_themename').html(name);
	$('#delete_theme_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/delete/theme/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function securityAskChangeTheme(name,id)
{
	$('#change_theme_dialog #change_themename').html(name);
	$('#change_theme_dialog').dialog({modal: true, resizable: false, buttons: { "Aktivera": function() {  goto(host + "admin/save/theme/" + id + "/" + name); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function securityAskDeleteForm(name,id)
{
	$('#delete_form_dialog #delete_formtitle').html(name);
	$('#delete_form_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/delete/form/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function securityAskDeleteFormResult(name,id)
{
	$('#delete_form_dialog #delete_formtitle').html(name);
	$('#delete_form_dialog').dialog({modal: true, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/delete/form_result/" + id); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function installPlugin(name,slug)
{
	$('#install_plugin_dialog #install_pluginname').html(name);
	$('#install_plugin_dialog').dialog({modal: true, width:500, resizable: false, buttons: { "Jag är medveten om riskerna, installera tillägget!": function() {  goto(host + "admin/pluginactions/install/" + slug); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function uninstallPlugin(name,slug)
{
	$('#uninstall_plugin_dialog #uninstall_pluginname').html(name);
	$('#uninstall_plugin_dialog').dialog({modal: true, width:500, resizable: false, buttons: { "Avinstallera": function() {  goto(host + "admin/pluginactions/uninstall/" + slug); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function deletePlugin(name,slug)
{
	$('#delete_plugin_dialog #delete_pluginname').html(name);
	$('#delete_plugin_dialog').dialog({modal: true, width:500, resizable: false, buttons: { "Radera": function() {  goto(host + "admin/pluginactions/delete/" + slug); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function previewTheme()
{
	showDialog("no_function");
}
function opennewfileDialog()
{
	$('#newfile_dialog').dialog({modal: true, width: 400, height: 180, resizable: false, buttons: { "Ladda upp": function() {  $('#newfileform').submit(); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function attach_file( p_script_url ) {
      // create new script element, set its relative URL, and load it
      script = document.createElement( 'script' );
      script.src = p_script_url;
      document.getElementsByTagName( 'head' )[0].appendChild( script );
}
function startLoad(string)
{
	$("#saving").show();
	$("#saving p").html(string);
}
function stopLoad()
{
	var t = setTimeout("hideSaving()", 500);	
}
function hideSaving()
{
	$("#saving").hide();
}
function nl(string, rep)
{
	return string.replace(/(\r)|(\n)|(\r\n)/mg, ""+ rep);
}
function clickUser(id)
{
	goto(host + "admin/user/" + id);
}
function clickPage(id)
{
	goto(host + "admin/page/" + id);
}
function clickNews(id)
{
	goto(host + "admin/new/" + id);
}
function clickForm(id)
{
	goto(host + "admin/form/" + id);
}
function clickFormResult(id)
{
	goto(host + "admin/form_result/" + id);
}
function clickDocument(id, element, name, size)
{
	$('#showfile_dialog #filename').html(name);
	$('#showfile_dialog #filesize').html(size);
	$('#showfile_dialog #filelink').html(name);
	$('#showfile_dialog').dialog({modal: true, resizable: false, width: 450, height: 220, buttons: { "Öppna": function() { gotonew(host + "upload/documents/" + name); }, "Radera": function() { deleteDocument(id, element, name, this); }, "Stäng": function() { $(this).dialog("close"); } } });	
}
function deleteDocument(id, element, name, dialog)
{
	$(dialog).dialog("close");
	attach_file(host + "admin/autoupdate/deleteDocument.php?docid=" + id + "&document=" + name);
	$(element).slideUp('slow');
}
function editSlug()
{
	edit_slug_form.slugtext.value = customerform.slug.value;
	$('#edit_slug_dialog').dialog({modal: true, resizable: false, height: 240, width:450, buttons: { "Ändra": function() { getPageSlug(edit_slug_form.slugtext.value, pageid); $(this).dialog("close"); }, "Avbryt": function() { $(this).dialog("close"); } } });	
}
function editNewsSlug()
{
	edit_slug_form.slugtext.value = customerform.slug.value;
	$('#edit_slug_dialog').dialog({modal: true, resizable: false, height: 240, width:450, buttons: { "Ändra": function() { getNewsSlug(edit_slug_form.slugtext.value, newsid); $(this).dialog("close"); }, "Avbryt": function() { $(this).dialog("close"); } } });	
}
// Denna funktionen kan kanske tas bort
function validateLostpw(form)
{
	var ret = true;
	var text = ""
	var errors = 0;
	if(form.username.value == "" || form.username.value == " ")
	{
		text += " - Användarnamn är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	
	if(errors != 0)
	{
		alert(errors + " fel hittades\n\n" + text);
	}
	
	return ret;
}
function validateUser(form)
{
	var ret = true;
	var text = ""
	var errors = 0;
	if(form.username.value == "" || form.username.value == " ")
	{
		text += " - Användarnamn är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	if(form.name.value == "" || form.name.value == " ")
	{
		text += " - Namn är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	if(form.email.value == "" || form.email.value == " ")
	{
		text += " - E-postadress är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	if(form.user_password.value != "" && form.user_password.value != form.user_password_again.value)
	{
		text += " - Lösenorden stämmer inte överens med varandra.\n";
		ret = false;
		errors++;
	}
	if(form.userid.value == "Nytt konto" && (form.user_password.value == "" || form.user_password.value == " "))
	{
		text += " - Lösenord är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	
	if(errors != 0)
	{
		alert(errors + " fel hittades\n\n" + text);
	}
	
	return ret;
}
function validateMenu(form)
{
	var ret = true;
	var text = ""
	var errors = 0;
	if(form.title.value == "" || form.title.value == " ")
	{
		text += " - Rubrik är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	if(form.menulink.value == "" || form.menulink.value == " ")
	{
		text += " - Länk är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	if(form.menulink.value.indexOf("http://") == -1 && form.menulink.value != "#")
	{
		text += " - Länken måste innehålla http:// i början.\n";
		ret = false;
		errors++;
	}
	
	if(errors != 0)
	{
		alert(errors + " fel hittades\n\n" + text);
	}
	
	return ret;
}
function validateSubMenu(form)
{
	var ret = true;
	var text = ""
	var errors = 0;
	if(form.subtitle.value == "" || form.subtitle.value == " ")
	{
		text += " - Rubrik är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	if(form.submenulink.value == "" || form.submenulink.value == " ")
	{
		text += " - Länk är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	if(form.submenulink.value.indexOf("http://") == -1)
	{
		text += " - Länken måste innehålla http:// i början.\n";
		ret = false;
		errors++;
	}
	
	if(errors != 0)
	{
		alert(errors + " fel hittades\n\n" + text);
	}
	
	return ret;
}
function validatePageform(form, standard)
{
	var ret = true;
	var text = ""
	var errors = 0;
	if(form.title.value == "" || form.title.value == " " || form.title.value == "" + standard)
	{
		text += " - Rubrik är ett obligatoriskt fält.\n";
		ret = false;
		errors++;
	}
	if(form.slug.value == "" || form.slug.value == " ")
	{
		text += " - Länken är felaktig.\n";
		ret = false;
		errors++;
	}
	if(form.type.value == "iframe" && (form.iframelink.value == "" || form.iframelink.value == " " || form.iframelink.value == "" + standardurl))
	{
		text += " - URL till en sida som ska visas saknas.\n";
		ret = false;
		errors++;
	}
	
	if(errors != 0)
	{
		alert(errors + " fel hittades\n\n" + text);
	}
	else if(oldSlug != form.slug.value && form.pageid.value != "Ny sida")
	{
		if(!confirm("VARNING!\n\nDu håller på att ändra länken på en befintlig sida. Detta kan vilseleda besökare då länkar från andra hemsidor blir inaktiva. Alla länkar i menyn på sidan uppdateras automatiskt.\nVill du återställa länken till det normala, klicka på avbryt.\n\nÄr du säker på att du vill fortsätta?"))
		{
			ret = false;
			customerform.slug.value = oldSlug;
			var targetDiv = document.getElementById("pagelink");
			targetDiv.innerHTML = "Länk: "+ host + oldSlug + " <a href='#' id='edit_slug_link' onclick='javascript: editSlug();'>Ändra</a>";
		}
	}
	
	if(form.type.value == "html")
	{
		rteFormHandler();
	}
	return ret;
}
function validateNewsform(form, standard, standardurl)
{
	var ret = true;
	var text = ""
	var errors = 0;
	if(form.title.value == "" || form.title.value == " " || form.title.value == "" + standard)
	{
		text += " - Rubrik är ett obligatoriskt fält.\n";
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
	else if(oldSlug != form.slug.value && form.newsid.value != "Ny nyhet")
	{
		if(!confirm("VARNING!\n\nDu håller på att ändra länken på en befintlig nyhet. Detta kan vilseleda besökare då länkar från andra hemsidor blir inaktiva.\nVill du återställa länken till det normala, klicka på avbryt.\n\nÄr du säker på att du vill fortsätta?"))
		{
			ret = false;
			customerform.slug.value = oldSlug;
			var targetDiv = document.getElementById("pagelink");
			targetDiv.innerHTML = "Länk: "+ host + oldSlug + " <a href='#' id='edit_slug_link' onclick='javascript: editSlug();'>Ändra</a>";
		}
	}
	rteFormHandler();
	
	return ret;
}
function validateFormform(form, standard, standardurl)
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
	if(errors != 0)
	{
		alert(errors + " fel hittades\n\n" + text);
	}
	
	return ret;
}
function getPageSlug(string, id)
{
	attach_file(host + "admin/autoupdate/getPageSlug.php?id=" + id + "&string=" + string);
}
function getNewsSlug(string, id)
{
	attach_file(host + "admin/autoupdate/getNewsSlug.php?id=" + id + "&string=" + string)
}
function setPageType(type)
{
	if(type == "html")
	{
		$("#RTEditorBox").show();
		$("#iFrameBox").hide();
	}
	else if(type == "iframe")
	{
		$("#RTEditorBox").hide();
		$("#iFrameBox").show();
	}
}
$(document).ready(
	function() {
		$("#mainmenu_sort").sortable({
			axis: 'y',
			update : function () {
				startLoad("Sparar");
				serial = $('#mainmenu_sort').sortable('serialize');
				$.ajax({
					url: host + "admin/autoupdate/sortmenu.php",
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
function activatePlugin(slug)
{
	goto(host + "admin/pluginactions/activate/" + slug);
}
function inactivatePlugin(slug)
{
	goto(host + "admin/pluginactions/inactivate/" + slug);
}