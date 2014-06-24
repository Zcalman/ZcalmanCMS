/* Awesome JavaScript */

var targetElementId = "form_sort";


var targetElement = document.getElementById(targetElementId);
var newFormObjCount = 1;
var editFormObject = null;

$(document).ready(function() {
	setGeneratedContentHeight();
	$('#' + targetElementId).sortable({
		containment: "parent",
		forcePlaceholderSize: true,
		axis: 'y',
		update: function(e, o) { changed = true; },
	});
	$(document).on('click', '.formObj', function() { showEditFormObjectDialog(this); });
});

function showEditFormObjectDialog(object) {
	var objType = object.getAttribute("type");
	var titleEl = object.children[0];
	$('#formobjectdialog_title').val(titleEl.innerHTML);
	var selOpts = "";
	var htmlReturn = "<p>Namn</p><input type=\"text\" id=\"formobjectdialog_title\" value=\""+ titleEl.innerHTML +"\" class=\"textfield\" style=\"width:200px;\" />";
	switch(objType) {
		case 'textfield':
			var width = parseFloat(object.children[1].style['width']);
			htmlReturn += "<p>Bredd</p><input type=\"text\" id=\"formobjectdialog_width\" value=\"" +width+ "\" style=\"text-align:right;width:70px;\" /><em>px</em>";
		break;
		case 'textbox':
			var width = parseFloat(object.children[1].style['width']);
			var height = parseFloat(object.children[1].style['height']);
			htmlReturn += "<p>Höjd</p><input type=\"text\" id=\"formobjectdialog_height\" value=\"" +height+ "\" style=\"text-align:right;width:70px;\" /><em>px</em>";
			htmlReturn += "<p>Bredd</p><input type=\"text\" id=\"formobjectdialog_width\" value=\"" +width+ "\" style=\"text-align:right;width:70px;\" /><em>px</em>";
		break;
		case 'list':
			var width = parseFloat(object.children[1].style['width']);
			htmlReturn += "<p>Bredd</p><input type=\"text\" id=\"formobjectdialog_width\" value=\"" +width+ "\" style=\"text-align:right;width:70px;\" /><em>px</em>";
			htmlReturn += "<p>Förvalt alternativ</p><div class=\"dropbox\"><select class=\"dropdown\" id=\"formobjectdialog_options_selected\" style=\"width:200px;\"></select></div>";
			htmlReturn += "<p>Alternativ ";
			htmlReturn += "<img src='"+ host +"admin/images/icons/add.png' alt='Lägg till alternativ' onclick='javascript: addOption();' style='border:0px; margin-left: 5px; margin-top:4px;' /></p>";
			htmlReturn += "<div id=\"formobjectdialog_options\">";
			var objSel = object.children[1];
			for(var i = 0; i < objSel.children.length; i++) {
				htmlReturn += "<div><input type=\"text\" value=\""+ objSel.children[i].innerHTML +"\" onchange='javascript: updateSelectedList();' class=\"textfield\" style=\"width:200px; margin-bottom:10px;\" />";
				htmlReturn += "<img src='"+ host +"admin/images/icons/delete.png' alt='Ta bort alternativ' onclick='javascript: removeNode(this.parentNode);' style='border:0px; margin-left: 5px; margin-top:4px;' />";
				htmlReturn += "<img src='"+ host +"admin/images/icons/arrow_up_down.png' alt='Flytta genom att röra musen' style='border:0px; margin-left: 5px; margin-top:4px;' /></div>";
				selOpts += "<option ";
				if(objSel.children[i].selected) {
					selOpts += "selected=\"selected\"";
				}
				selOpts += " value=\""+ objSel.children[i].innerHTML +"\">"+ objSel.children[i].innerHTML +"</option>";
			}
			htmlReturn += "</div>";
		break;
		case 'radio':
			htmlReturn += "<p>Förvalt alternativ</p><div class=\"dropbox\"><select class=\"dropdown\" id=\"formobjectdialog_options_selected\" style=\"width:200px;\"></select></div>";
			htmlReturn += "<p>Alternativ ";
			htmlReturn += "<img src='"+ host +"admin/images/icons/add.png' alt='Lägg till alternativ' onclick='javascript: addOption();' style='border:0px; margin-left: 5px; margin-top:4px;' /></p>";
			htmlReturn += "<div id=\"formobjectdialog_options\">";
			var objSel = object.children[1];
			selOpts += "<option value='0x0'>Inget</option>";
			for(var i = 0; i < objSel.children.length; i++) {
				if(objSel.children[i].tagName == "LABEL") {
					htmlReturn += "<div><input type=\"text\" value=\""+ objSel.children[i].innerHTML +"\" onchange='javascript: updateSelectedList();' class=\"textfield\" style=\"width:200px; margin-bottom:10px;\" />";
					htmlReturn += "<img src='"+ host +"admin/images/icons/delete.png' alt='Ta bort alternativ' onclick='javascript: removeNode(this.parentNode);' style='border:0px; margin-left: 5px; margin-top:4px;' />";
					htmlReturn += "<img src='"+ host +"admin/images/icons/arrow_up_down.png' alt='Flytta genom att röra musen' style='border:0px; margin-left: 5px; margin-top:4px;' /></div>";
					selOpts += "<option ";
					if(objSel.children[i-1].checked) {
						selOpts += "selected=\"selected\"";
					}
					selOpts += " value=\""+ objSel.children[i].innerHTML +"\">"+ objSel.children[i].innerHTML +"</option>";
				}
			}
			htmlReturn += "</div>";
		break;
		case 'checkbox':
			var objCheckbox = object.children[1];
			htmlReturn += "<br /><br /><input type=\"checkbox\" ";
			if(objCheckbox.checked) {
				htmlReturn += " checked=\"checked\" ";
			}
			htmlReturn += " id=\"formobjectdialog_opt_checked\" /><label for=\"formobjectdialog_opt_checked\">Vald från start</label>";
			htmlReturn += "<p>Svar</p><input type=\"text\" id=\"formobjectdialog_opt\" value=\"" + object.children[2].innerHTML + "\" class=\"textfield\" style=\"width:200px;\" />";
		break;
		default:
		break;
	}
	
	editFormObject = object;
	
	$('#edit_formobject_dialog').html(htmlReturn);
	
	if(objType == "list") {
		$('#formobjectdialog_options').sortable({
			containment: "#edit_formobject_dialog",
			forcePlaceholderSize: true,
			scroll: false,
			axis: 'y',
			update: function(e, o) { changed = true; },
		});
		$('#formobjectdialog_options_selected').html(selOpts);
	}
	if(objType == "radio") {
		$('#formobjectdialog_options').sortable({
			containment: "#edit_formobject_dialog",
			forcePlaceholderSize: true,
			scroll: false,
			axis: 'y',
			update: function(e, o) { changed = true; },
		});
		$('#formobjectdialog_options_selected').html(selOpts);
	}
	$('#edit_formobject_dialog').dialog({modal: true, width:300, resizable: false, buttons: { "Ta bort": function() { removeObject(object); }, "Utför": function() { doEditFormObject(); }, "Avbryt": function() { $(this).dialog("close"); } } });
}

function doEditFormObject() {
	var object = editFormObject;
	var objType = object.getAttribute("type");
	var newTitle = escapeText($('#formobjectdialog_title').val());
	var htmlReturn = "<h3>" +newTitle+ "</h3>";
	switch(objType) {
		case 'textfield':
			var newWidth = parseFloat($('#formobjectdialog_width').val());
			htmlReturn += "<input type=\"text\" name=\"" +newTitle+ "\" disabled=\"disabled\" style=\"width:"+ newWidth +"px;\" />";
		break;
		case 'textbox':
			var newWidth = parseFloat($('#formobjectdialog_width').val());
			var newHeight = parseFloat($('#formobjectdialog_height').val());
			htmlReturn += "<textarea disabled=\"disabled\" name=\"" +newTitle+ "\" style=\"width:"+ newWidth +"px; height:"+ newHeight +"px;resize:none;\"></textarea>";
		break;
		case 'list':
			var newWidth = parseFloat($('#formobjectdialog_width').val());
			htmlReturn += "<select name=\"" +newTitle+ "\" disabled=\"disabled\" style=\"width:"+ newWidth +"px;\">";
			var optionsDiv = document.getElementById("formobjectdialog_options");
			var optionsSel = document.getElementById("formobjectdialog_options_selected");
			for(var i = 0; i < optionsDiv.children.length; i++) {
				var opt = escapeText(optionsDiv.children[i].children[0].value);
				htmlReturn += "<option ";
				if(opt == optionsSel.value) {
					htmlReturn += "selected=\"selected\"";
				}
				htmlReturn += " value=\""+opt+"\">"+opt+"</option>";
			}
			htmlReturn += "</select>";
		break;
		case 'radio':
			htmlReturn += "<div class=\"formRadioButtons\">";
			var optionsDiv = document.getElementById("formobjectdialog_options");
			var optionsSel = document.getElementById("formobjectdialog_options_selected");
			for(var i = 0; i < optionsDiv.children.length; i++) {
				var opt = escapeText(optionsDiv.children[i].children[0].value);
				htmlReturn += "<input type=\"radio\" disabled=\"disabled\" ";
				if(opt == optionsSel.value) {
					htmlReturn += "checked=\"checked\"";
				}
				htmlReturn += " id=\"formRadio-"+ newTitle +"-"+ opt +"\" name=\""+ newTitle +"\"  value=\""+opt+"\" /><label for=\"formRadio-"+ newTitle +"-"+ opt +"\">"+opt+"</label>";
			}
			htmlReturn += "</div>";
		break;
		case 'checkbox':
			var opt = escapeText(document.getElementById("formobjectdialog_opt").value);
			var optChecked = document.getElementById("formobjectdialog_opt_checked").checked;
			htmlReturn += "<input type=\"checkbox\" ";
			if(optChecked) {
				htmlReturn += " checked=\"checked\" ";
			}
			htmlReturn += " disabled=\"disabled\" id=\"formCheckbox-"+ newTitle +"\" name=\""+ newTitle +"\" value=\""+ opt +"\" /><label for=\"formCheckbox-"+ newTitle +"\">"+ opt +"</label>";
		break;
		default:
		break;
	}
	object.innerHTML = htmlReturn;
	$('#edit_formobject_dialog').dialog("close");
}

function addFormObject(type) {
	var divEl = document.createElement("DIV");
	divEl.setAttribute("id", "formObj-new_" + newFormObjCount);
	divEl.setAttribute("class", "formObj");
	divEl.setAttribute("type", type);	
	var titleEl = document.createElement("H3");
	var formObj;
	var label;
	
	switch(type) {
		case 'textfield':
			titleEl.innerHTML = "Textfält";
			formObj = document.createElement("INPUT");
			formObj.setAttribute("type", "text");
			formObj.setAttribute("name", "Textfält");
			formObj.setAttribute("disabled", "disabled");
			formObj.setAttribute("style", "width:200px;");
		break;
		case 'textbox':
			titleEl.innerHTML = "Textruta";
			formObj = document.createElement("TEXTAREA");
			formObj.setAttribute("name", "Textruta");
			formObj.setAttribute("disabled", "disabled");
			formObj.setAttribute("style", "width:200px;height:100px;resize: none;");
		break;
		case 'list':
			titleEl.innerHTML = "Lista";
			formObj = document.createElement("SELECT");
			formObj.setAttribute("name", "Lista");
			formObj.setAttribute("disabled", "disabled");
			formObj.setAttribute("style", "width:200px;");
			var optObj1 = document.createElement("OPTION");
			var optObj2 = document.createElement("OPTION");
			optObj1.setAttribute("selected", "selected");
			optObj1.setAttribute("value", "Val 1");
			optObj1.innerHTML = "Val 1";
			optObj2.setAttribute("value", "Val 2");
			optObj2.innerHTML = "Val 2";
			formObj.appendChild(optObj1);
			formObj.appendChild(optObj2);
		break;
		case 'radio':
			titleEl.innerHTML = "Radioknappsgrupp";
			formObj = document.createElement("DIV");
			formObj.setAttribute("class", "formRadioButtons");
			var optObj1 = document.createElement("INPUT");
			optObj1.setAttribute("value", "Val 1");
			optObj1.setAttribute("type", "radio");
			optObj1.setAttribute("id", "formRadio" +newFormObjCount+ "-Val_1");
			optObj1.setAttribute("name", "formRadio" +newFormObjCount+ "-Val_1");
			optObj1.setAttribute("disabled", "disabled");
			optObj1.setAttribute("checked", "checked");
			var lbl1 = document.createElement("LABEL");
			lbl1.setAttribute("for", "formRadio" +newFormObjCount+ "-Val_1");
			lbl1.innerHTML = "Val 1";
			var optObj2 = document.createElement("INPUT");
			optObj2.setAttribute("value", "Val 2");
			optObj2.setAttribute("type", "radio");
			optObj2.setAttribute("id", "formRadio" +newFormObjCount+ "-Val_2");
			optObj2.setAttribute("name", "formRadio" +newFormObjCount+ "-Val_2");
			optObj2.setAttribute("disabled", "disabled");
			var lbl2 = document.createElement("LABEL");
			lbl2.setAttribute("for", "formRadio" +newFormObjCount+ "-Val_2");
			lbl2.innerHTML = "Val 2";
			formObj.appendChild(optObj1);
			formObj.appendChild(lbl1);
			formObj.appendChild(optObj2);
			formObj.appendChild(lbl2);
		break;
		case 'checkbox':
			titleEl.innerHTML = "Checkbox";
			formObj = document.createElement("INPUT");
			formObj.setAttribute("type", "checkbox");
			formObj.setAttribute("name", "Checkbox");
			formObj.setAttribute("disabled", "disabled");
			formObj.setAttribute("id", "formCheckbox-" + newFormObjCount);
			formObj.setAttribute("name", "formCheckbox-" + newFormObjCount);
			formObj.setAttribute("value", "Alternativ");
			label = document.createElement("LABEL");
			label.setAttribute("for", "formCheckbox-" + newFormObjCount);
			label.innerHTML = "Alternativ";
		break;
		default:
		break;
	}
	divEl.appendChild(titleEl);
	divEl.appendChild(formObj);
	if(type == "checkbox") { divEl.appendChild(label); }
	targetElement.appendChild(divEl);
	newFormObjCount++;
	changed = true;
	$('#' + targetElementId).sortable('refresh');
}

function removeObject(object) {
	removeNode(object);
	setGeneratedContentHeight();
	$('#' + targetElementId).sortable('refresh');
	$('#edit_formobject_dialog').dialog("close");
}

function setGeneratedContentHeight() {
	$('#' + targetElementId).css("min-height", "0px");
	$('#' + targetElementId).css("min-height", $('#' + targetElementId).css("height"));
}

function addOption() {
	var htmlReturn = "";
	htmlReturn += "<div><input type=\"text\" value=\"Nytt alternativ\" onchange='javascript: updateSelectedList();' class=\"textfield\" style=\"width:200px; margin-bottom:10px;\" />";
	htmlReturn += "<img src='"+ host +"admin/images/icons/delete.png' alt='Ta bort alternativ' onclick='javascript: removeNode(this.parentNode);' style='border:0px; margin-left: 5px; margin-top:4px;' />";
	htmlReturn += "<img src='"+ host +"admin/images/icons/arrow_up_down.png' alt='Flytta genom att röra musen' style='border:0px; margin-left: 5px; margin-top:4px;' /></div>";
	$('#formobjectdialog_options').append(htmlReturn);
	updateSelectedList();
}

function removeNode(node) {
	node.parentNode.removeChild(node);
}

function updateSelectedList() {
	var htmlReturn = "";
	var optionsDiv = document.getElementById("formobjectdialog_options");
	var optionsSel = document.getElementById("formobjectdialog_options_selected");
	for(var i = 0; i < optionsDiv.children.length; i++) {
		var opt = optionsDiv.children[i].children[0].value;
		htmlReturn += "<option ";
		if(opt == optionsSel.value) {
			htmlReturn += "selected=\"selected\"";
		}
		htmlReturn += " value=\""+opt+"\">"+opt+"</option>";
	}
	$('#formobjectdialog_options_selected').html(htmlReturn);
}

function saveForm(id) {
	startLoad('Sparar');
	var formElement = document.getElementById('form_sort');
	var objects = [];
	var searchEles = formElement.children;
	var objString = "";
	var htmlPrint = "\n<form method=\"post\" name=\"zect-hidden-form\" id=\"zect-hidden-form\" action=\"" + host + "admin/save/form/" + document.getElementById('formid').value + "\" style=\"visibilty:hidden;\">"; 
	for(var i = 0; i < searchEles.length; i++) {
		var obj = [];
		obj['id'] = searchEles[i].id;
		obj['type'] = searchEles[i].attributes.type.value;
		obj['formobjid'] = obj.id.slice(8);
		if(obj['formobjid'].indexOf("new") == 0) {
			obj['formobjid'] = obj['formobjid'].slice(0, 3);
		}
		obj['sort'] = i;
		obj['title'] = escapeText(searchEles[i].children[0].innerHTML);
		switch(obj.type) {
			case 'textbox':
				obj['settings'] = "width:" + searchEles[i].children[1].style.width + "|height:" + searchEles[i].children[1].style.height;
				obj['content'] = "";
			break;
			case 'textfield':
				obj['settings'] = "width:" + searchEles[i].children[1].style.width;
				obj['content'] = "";
			break;
			case 'list':
			var listValues = "";
			var tempSettings = "width:" + searchEles[i].children[1].style.width;
				for(var j = 0; j < searchEles[i].children[1].children.length; j++) {
					listValues += searchEles[i].children[1].children[j].value;
					if(searchEles[i].children[1].children[j].selected) {
						tempSettings += "|default:" + searchEles[i].children[1].children[j].value;
					}
					if(j+1 < searchEles[i].children[1].children.length) {
						listValues += "|";
					}
				}
				obj['settings'] = tempSettings;
				obj['content'] = listValues;
			break;
			case 'radio':
				var radioValues = "";
				var tempSettings = "";
				for(var j = 0; j < searchEles[i].children[1].children.length; j++) {
					if(searchEles[i].children[1].children[j].tagName == "INPUT") {
						radioValues += searchEles[i].children[1].children[j].value;
						if(searchEles[i].children[1].children[j].checked) {
							tempSettings += "default:" + searchEles[i].children[1].children[j].value;
						}
						if(j+2 < searchEles[i].children[1].children.length) {
							radioValues += "|";
						}
					}
				}
				obj['settings'] = tempSettings;
				obj['content'] = radioValues;
			break;
			case 'checkbox':
				var tempSettings = "unchecked";
				if(searchEles[i].children[1].checked) {
					tempSettings = "checked";
				}
				obj['settings'] = "default:" + tempSettings;
				obj['content'] = searchEles[i].children[1].value;
			break;
			default:
				//errors.push("Attribute 'type' missing or is invalid in element: #"+searchEles[i].id+" (No content selected)");
			break;
		}
		objects.push(obj);
	}
	
	htmlPrint += "<input type=\"hidden\" name=\"title\" value=\"" + document.getElementById('title').value + "\" />";
	var emailVal = document.getElementById('email').value.trim();
	if(emailVal != "") {
		htmlPrint += "<input type=\"hidden\" name=\"email\" value=\"" + emailVal + "\" />";
	}
	htmlPrint += "<input type=\"hidden\" name=\"submitmessage\" value=\"" + document.getElementById('submitmessage').value.trim() + "\" />";
	for(var i = 0; i < objects.length; i++) {
		htmlPrint += "<input type=\"hidden\" name=\"" + objects[i].id + "-content\" value=\"" + objects[i].content + "\" />"
		htmlPrint += "<input type=\"hidden\" name=\"" + objects[i].id + "-settings\" value=\"" + objects[i].settings + "\" />"
		htmlPrint += "<input type=\"hidden\" name=\"" + objects[i].id + "-meta\" value=\"id:" + objects[i].formobjid + "|type:" + objects[i].type + "|sort:" + objects[i].sort + "|title:" + objects[i].title + "\" />"
		objString += objects[i].id;
		if(i+1 < objects.length) {
			objString += "|";
		}
	}
	htmlPrint += "<input type=\"hidden\" name=\"object-string\" value=\"" + objString + "\" />";
	htmlPrint += "</form>";
	$('body').append(htmlPrint);
	var formId = document.getElementById("zect-hidden-form");
	changed = false;
	formId.submit();	
}


function escapeText(text) {
	text = text.replace(/"/g, "&#34;");
	text = text.replace(/'/g, "&#39;");
	text = text.replace(/\\/g, "&#92;");
	return text;
}