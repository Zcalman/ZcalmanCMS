// Javascript
$(document).ready(
	function() {
		$("#update_box").hide();
		$("#run_update_button").click(function() {
			$("#run_update_button").hide();
			$("#update_box").show();
			run_update();
		});
		$("#force_update_button").click(function() {
			$("#force_update_button").hide();
			$("#update_box").show();
			force_update();
		});
	}
);

function run_update()
{
	attach_file(host + "admin/updatescript/updatescript.php");	
}

function force_update()
{
	attach_file(host + "admin/updatescript/force_updatescript.php");	
}

function update_finished(num_errors)
{
	if(num_errors == 0)
	{
		goto(host + "admin/update-result/succed");
	}
	else
	{
		goto(host + "admin/update-result/errors/" + num_errors);
	}

}