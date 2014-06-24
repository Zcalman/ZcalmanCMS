$(document).ready(
	function() {
		$("#category_dropdown").change(function() {
			var cat = $("#category_dropdown").val();
			goto(host + "admin/plugin/zc_imagegallery/start/" + cat);
		});	
	}
);

function showImageInfo(folder, file, id)
{
	$('#image_info_dialog #image_info_title').html(host + "upload/zc_imagegallery/"+ folder + "/" + file);
	$('#image_info_dialog #image_info_image').html("<img src='" + host + "upload/zc_imagegallery/"+ folder + "/" + file +"' style='width:375px; max-height:375px;' />");
	$('#image_info_dialog').dialog({modal: true, resizable: false, width: 700, buttons: { "Radera bilden": function() {  deleteImage(folder, file, id); $(this).dialog("close"); }, "Stäng": function() { $(this).dialog("close"); } } });
}
function securityAskDeleteGallery(folder, name)
{
	$('#gallery_delete_dialog #gallery_delete_folder').html(name);
	$('#gallery_delete_dialog').dialog({modal: true, resizable: false, width: 400, buttons: { "Radera galleriet": function() {  deleteGallery(folder); $(this).dialog("close"); }, "Avbryt": function() { $(this).dialog("close"); } } });
}
function deleteImage(folder, file, id)
{
	attach_file(host + "plugins/zc_imagegallery/deleteimage.php?folder=" + folder + "&file=" + file);
	$(id).hide();
}
function deleteGallery(folder)
{
	goto(adminhost + "plugin/zc_imagegallery/deletegallery/" + folder);
}