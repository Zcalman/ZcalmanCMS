<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
?>
<div id="dialogs">
    <div id="no_function" title="FAIL">Den här funktionen saknas för tillfället!</div>
	<?PHP 
		if(!isset($page))
		{
			$page = "";
		}
		else
		{
			?>
    <div id="confirmLeaveDialog" title="Säkerhetsfråga">Du har ändrat något på sidan som inte har sparats.<br /><br />Är du säker på att du vill fortsätta?</div>
    <div id="logoutdialog" title="Logga ut">Du kommer nu loggas ut, klicka på avbryt för att stanna inloggad.</div>
    		<?PHP			
		}
		if($page == "menu")
        {
			?>
    <div id="delete_menu_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera huvudmenylänken <span style="font-weight:bold;" id="delete_menutitle"></span>?<br /><br />Alla tillhörande undermenylänkar kommer också försvinna.</div>
    <div id="delete_submenu_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera undermenylänken <span style="font-weight:bold;" id="delete_submenutitle"></span>?</div>
    <div id="new_submenulink_dialog" title="Ny undermenyslänk">
    	<form name="new_submenulink_form" id="new_submenulinkform" action="<?PHP host(); ?>admin/save/submenu" onsubmit="javascript: return validateSubMenu(this);" method="post">
        <input type="hidden" name="parent" id="parent" value="<?PHP echo $menuid; ?>" />
        <table class="settingstable">
            <tr>
                <td class="settingstitle">
                    Rubrik
                </td>
                <td class="settingsfield">
        			<input type="text" name="subtitle" id="subtitle" class="textfield" />
                </td>
            </tr>
            <tr>
                <td class="settingstitle">
                    Länk <span class="italic table-description">(Inklusive http://)</span>
                </td>
                <td class="settingsfield">
        			<input type="text" name="submenulink" id="submenulink" class="textfield" />
                </td>
            </tr>
            <tr>
                <td class="settingstitle">
                    Öppna länken i
                </td>
                <td class="settingsfield">
                	<div class="dropbox">
                        <select name="submenutarget" class="dropdown" id="submenutarget">
                            <option <?PHP setSel($target, "_self"); ?>value="_self">Samma fönster</option>
                            <option <?PHP setSel($target, "_blank"); ?>value="_blank">Nytt fönster</option>
                        </select>
                    </div>
                </td>
            </tr>
        </table>
        </form>
    </div>
    		<?PHP
		}
		elseif($page == "page")
        {
            ?>
    <div id="delete_page_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera sidan <span style="font-weight:bold;" id="delete_pagetitle"></span>?</div>
    <div id="edit_slug_dialog" title="Ändra länk">
        <p>När du trycker på ändra uppdateras bara länken tillfälligt. Kontrollera sedan att länken blir som du vill. När du är klar med dina ändringar, tryck spara.</p>
        <form name="edit_slug_form" id="edit_slug_form" action="#" onsubmit='javascript: getPageSlug(this.slugtext.value, "<?PHP echo $pageid; ?>"); $("#edit_slug_dialog").dialog("close"); return false;' method="post">
        <br /><strong>Länk</strong><br />
            <?PHP host(); ?><input type="text" name="slugtext" id="slugtext" class="textfield" />
        </form>
    </div>   
            <?PHP
        }
		elseif($page == "new")
		{
			?>
    <div id="edit_slug_dialog" title="Ändra länk">
        <p>När du trycker på ändra uppdateras bara länken tillfälligt. Kontrollera sedan att länken blir som du vill. När du är klar med dina ändringar, tryck spara.</p>
        <form name="edit_slug_form" id="edit_slug_form" action="#" onsubmit='javascript: getNewsSlug(this.slugtext.value, "<?PHP echo $newsid; ?>"); $("#edit_slug_dialog").dialog("close"); return false;' method="post">
        <br /><strong>Länk</strong><br />
            <?PHP host(); echo "article/"; ?><input type="text" name="slugtext" id="slugtext" class="textfield" />
        </form>
    </div>
    <div id="delete_news_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera nyheten <span style="font-weight:bold;" id="delete_newstitle"></span>?</div>
            <?PHP
        }
		elseif($page == "documents")
		{
			?>
    <div id="newfile_dialog" title="Ladda upp nytt dokument">
        <form name="newfileform" id="newfileform" action="<?PHP host(); ?>admin/uploadfile/" enctype="multipart/form-data" method="post">
        <br /><strong>Fil</strong>
        <input type="file" name="uplfile" id="uplfile" class="textfield" style="width:370px;" />
        </form>
    </div>   
    <div id="showfile_dialog" title="Information om fil">
        <strong>Fil: </strong><span id="filename"></span><br />
        <strong>Storlek: </strong><span id="filesize"></span><br />
        <strong>Direktlänk: </strong><br /><p style="width:390px; float:left;"><?PHP host(); ?>upload/documents/<span id="filelink"></span></p>
    </div> 
            <?PHP
        }
		elseif($page == "user")
		{
			?>
    <div id="delete_user_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera användaren <span style="font-weight:bold;"id="delete_username"></span>?</div>
            <?PHP
        }
		elseif($page == "themes")
		{
			?>
    <div id="delete_theme_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera temat <span style="font-weight:bold;"id="delete_themename"></span>?</div>
    <div id="change_theme_dialog" title="Säkerhetsfråga">Är du säker på att du vill aktivera temat <span style="font-weight:bold;"id="change_themename"></span>?</div>
            <?PHP
		}
		elseif($page == "plugins")
		{
			?>
    <div id="install_plugin_dialog" title="Säkerhetsfråga">Är du säker på att du vill installera tillägget <span style="font-weight:bold;"id="install_pluginname"></span>?<br />Installation av detta tillägg sker på egen risk.</div>
    <div id="uninstall_plugin_dialog" title="Säkerhetsfråga">Är du säker på att du vill avinstallera tillägget <span style="font-weight:bold;"id="uninstall_pluginname"></span>?<br />Data som hör ihop med tillägget kan komma att raderas.</div>
    <div id="delete_plugin_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera tillägget <span style="font-weight:bold;"id="delete_pluginname"></span>?<br />Data som hör ihop med tillägget kommer att raderas.</div>
            <?PHP
		}
		elseif($page == "form")
		{
			?>
    <div id="delete_form_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera hela formuläret <span style="font-weight:bold;" id="delete_formtitle"></span>?</div>
    <div id="edit_formobject_dialog" title="Editera objekt"></div>
            <?PHP
        }
		elseif($page == "form_result")
		{
			?>
    <div id="delete_form_dialog" title="Säkerhetsfråga">Är du säker på att du vill radera detta svaret på formuläret <span style="font-weight:bold;" id="delete_formtitle"></span>?</div>
    <div id="edit_formobject_dialog" title="Editera objekt"></div>
            <?PHP
        }
	?>
</div>
