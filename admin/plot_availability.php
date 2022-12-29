<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "plot_availability";

echo $validation->read_permission();

@$projectid = $validation->input_validate($_GET['projectid']);
@$blockid = $validation->input_validate($_GET['blockid']);

$where_query = "";
if($projectid != "")
{
	$where_query .= " and projectid = '$projectid'";
}
if($blockid != "")
{
	$where_query .= " and blockid = '$blockid'";
}

$plotResult = $db->view("*", "mlm_plots", "plotid", $where_query, "title asc");
?>
<!DOCTYPE html>
<html LANG="en">
<head>
<?php include_once("inc_title.php"); ?>
<?php include_once("inc_files.php"); ?>
<script>
function fetch_block()
{
	$.ajax({
		type: 'post',
		url: 'fetch_block2.php',
		data:
		{
			projectid: $("#projectid").val(),
			blockid: "<?php echo $blockid; ?>",
			mode: "<?php echo $mode; ?>"
		},
		success: function(result)
		{
			$("#block_area").html(result);
		}
	});
}

$(document).ready(function(){
	fetch_block();
});
</script>
</head>
<body>
<div ID="wrapper">
<?php include_once("inc_header.php"); ?>
<div ID="page-wrapper">
<div CLASS="container-fluid">
<div CLASS="row">
	<div CLASS="col-lg-12">
		<h1 CLASS="page-header">Plots Inventory</h1>
	</div>
</div>

<form name="form_actions" method="GET" action="plot_availability.php" ENCTYPE="MULTIPART/FORM-DATA">
<div class="row">
	<div class="col-sm-12 mb-0">
		<div class="form-inline">
			<select NAME="projectid" CLASS="form-control mb_inline mb-2" ID="projectid" onChange="fetch_block();" >
				<option VALUE="">--select project--</option>
				<?php
				$projectResult = $db->view('projectid,title', 'mlm_projects', 'projectid', "and status='active'", 'title asc');
				foreach($projectResult['result'] as $projectRow)
				{
				?>
					<option VALUE="<?php echo $validation->db_field_validate($projectRow['projectid']); ?>" <?php if($projectRow['projectid'] == $projectid) echo "selected"; ?>><?php echo $validation->db_field_validate($projectRow['title']); ?></option>
				<?php
				}
				?>
			</select>
			<div id="block_area"></div>
			<button type="submit" class="btn btn-default mb_inline btn-sm btn_submit mb-2 mr-4">Apply</button>
		</div>
	</div>
</div>

<div class="table-responsive mt-4">
	<div class="row plot_block">
		<?php
		if($plotResult['num_rows'] >= 1)
		{
			foreach($plotResult['result'] as $plotRow)
			{
				$userid = $plotRow['userid'];
				$userQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid}'");
				$userRow = $userQueryResult['result'][0];
				
				$projectid = $plotRow['projectid'];
				$projectResult = $db->view("title,projectid", "mlm_projects", "projectid", "and projectid='{$projectid}'");
				$projectRow = $projectResult['result'][0];
				
				$blockid = $plotRow['blockid'];
				$blockResult = $db->view("title,blockid", "mlm_blocks", "blockid", "and blockid='{$blockid}'");
				$blockRow = $blockResult['result'][0];
		?>
			<div class="plot_main_col col-6 col-sm-2 col-md-1 col-sm-1 mb-2">
				<div class="plot_col plot_green">
					<div class="tooltip2">
						<?php echo $validation->db_field_validate($plotRow['title']); ?>
						<span class="tooltiptext">
							Plot Type: <?php echo $validation->db_field_validate($plotRow['plot_type']); ?>
							<br />
							Amount (Per Square Yard): <?php echo $validation->db_field_validate($plotRow['amount_sqryard']); ?>
							<br />
							Plot Size: <?php echo $validation->db_field_validate($plotRow['plot_size']); ?>
							<br />
							Plot Amount: <?php echo $validation->db_field_validate($plotRow['amount']); ?>
							<br />
							Dimensions: <?php echo $validation->db_field_validate($plotRow['dimensions']); ?>
							<br />
						</span>
					</div>
				</div>
			</div>
		<?php
			}
		}
		else
		{
		?>
			<h4 class="text-center">No Record is Available!	</h4>
		<?php
		}
		?>
	</div>
</div>
</form>

</div>
</div>
</div>
</body>
</html>