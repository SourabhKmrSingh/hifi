<?php

include_once("inc_config.php");

include_once("login_user_check.php");



$_SESSION['active_menu'] = "plot_inventory";



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



$plotResult = $db->view("*", "mlm_plots", "plotid", $where_query, "plotid asc");

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

		<h1 CLASS="page-header">Plots Inventory <?php if($_SESSION['per_write'] == "1") { ?><a href="plot_inventory_form.php?mode=insert" class="btn mb_inline btn-sm btn_submit ml-3">Add Inventory</a><?php } ?></h1>

	</div>

</div>



<form name="form_actions" method="GET" action="plot_inventory.php" ENCTYPE="MULTIPART/FORM-DATA">

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

			<button type="submit" class="btn  mb_inline btn-sm btn_submit mb-2 mr-4">Apply</button>

		</div>

	</div>

</div>



<div class="mt-4">

	<div class="row plot_block">

		<?php

		if($plotResult['num_rows'] >= 1)

		{

			foreach($plotResult['result'] as $plotRow)

			{

				$plotid = $plotRow['plotid'];

				$inventoryhistoryResult = $db->view('payment_type,name,regid', 'mlm_plots_inventory_history', 'historyid', "and inventoryid IN (select inventoryid from mlm_plots_inventory where plotid='$plotid' and status = 'active')", "historyid desc", "1");

				$inventoryhistoryRow = $inventoryhistoryResult['result'][0];

				if($inventoryhistoryRow['payment_type'] == "Token")

				{

					$box_color = "box_token";

					$booking_status = $inventoryhistoryRow['payment_type'];

				}

				else if($inventoryhistoryRow['payment_type'] == "Part Payment")

				{

					$box_color = "box_partpayment";

					$booking_status = $inventoryhistoryRow['payment_type'];

				}

				else if($inventoryhistoryRow['payment_type'] == "Booked")

				{

					$box_color = "box_booked";

					$booking_status = $inventoryhistoryRow['payment_type'];

				}

				else if($inventoryhistoryRow['payment_type'] == "EMI")

				{

					$box_color = "box_emi";

					$booking_status = $inventoryhistoryRow['payment_type'];

				}

				else if($inventoryhistoryRow['payment_type'] == "Registered")

				{

					$box_color = "box_register";

					$booking_status = $inventoryhistoryRow['payment_type'];

				}

				else

				{

					$box_color = "box_available";

					$booking_status = "Available";

				}

				

				$projectid = $plotRow['projectid'];

				$projectResult = $db->view("title,projectid", "mlm_projects", "projectid", "and projectid='{$projectid}'");

				$projectRow = $projectResult['result'][0];

				

				$blockid = $plotRow['blockid'];

				$blockResult = $db->view("title,blockid", "mlm_blocks", "blockid", "and blockid='{$blockid}'");

				$blockRow = $blockResult['result'][0];

				$registerRow = "";
				$iuserRegid = $inventoryhistoryRow['regid'];
				if($iuserRegid != ""){
					$registerResult = $db->view("*", "mlm_registrations", 'regid', " and regid ='{$iuserRegid}'");
					$registerRow = $registerResult['result'][0];
				}

		?>

			<div class="plot_main_col col-2 col-sm-2 col-md-1 col-sm-1 mb-2 p-1">

				<div class="plot_col <?php echo $box_color; ?>">

					<div class="tooltip2">

						<?php echo $validation->db_field_validate($plotRow['title']); ?>

						<span class="tooltiptext">

							<?php if($plotRow['title'] != "") { ?>

								<div class="tooltip_col1">Plot No.</div>

								<div class="tooltip_col2"><?php echo $validation->db_field_validate($plotRow['title']); ?></div>

							<?php } ?>

							

							<?php if($plotRow['plot_type'] != "") { ?>

								<div class="tooltip_col1">Type</div>

								<div class="tooltip_col2"><?php echo $validation->db_field_validate($plotRow['plot_type']); ?></div>

							<?php } ?>

							

							<?php if($plotRow['amount_sqryard'] != "" && $inventoryhistoryRow['payment_type']!= "Registered") { ?>

								<div class="tooltip_col1">Rate /SqYard</div>

								<div class="tooltip_col2">&#8377;<?php echo $validation->price_format($plotRow['amount_sqryard']); ?></div>

							<?php } ?>

							

							<?php if($plotRow['plot_size'] != "") { ?>

								<div class="tooltip_col1">Plot Size</div>

								<div class="tooltip_col2"><?php echo $validation->db_field_validate($plotRow['plot_size']); ?></div>

							<?php } ?>

							

							<?php if($plotRow['amount'] != "" && $inventoryhistoryRow['payment_type']!= "Registered") { ?>

								<div class="tooltip_col1">Plot Value</div>

								<div class="tooltip_col2">&#8377;<?php echo $validation->price_format($plotRow['amount']); ?></div>

							<?php } ?>


							

							

							<?php if($plotRow['dimensions'] != "") { ?>

								<div class="tooltip_col1">Dimensions</div>

								<div class="tooltip_col2"><?php echo $validation->db_field_validate($plotRow['dimensions']); ?></div>

							<?php } ?>

							

							<?php if($booking_status != "") { ?>

								<div class="tooltip_col1">Booking Status</div>

								<div class="tooltip_col2"><?php echo $validation->db_field_validate($booking_status); ?></div>

							<?php } ?>

							<?php if($inventoryhistoryRow['payment_type'] == "Registered") {?>

								<div class="tooltip_col1">Registry No.</div>

								<div class="tooltip_col2"><?php echo $validation->db_field_validate($plotRow['registry_number']); ?></div>

								<div class="tooltip_col1">Registry Date</div>

								<div class="tooltip_col2"><?php echo $plotRow['registry_date'] != ""  && $plotRow['registry_date'] != "0000-00-00" ? date("d-m-Y",strtotime($plotRow['registry_date'])) : ""; ?></div>

								<div class="tooltip_col1">Killa</div>

								<div class="tooltip_col2"><?php echo $validation->db_field_validate($plotRow['killa']); ?></div>

								<div class="tooltip_col1">Mutation</div>

								<div class="tooltip_col2"><?php echo $validation->db_field_validate($plotRow['mutation']); ?></div>


							<?php }?>

							

							<?php if($registerRow['first_name'] != "" and $inventoryhistoryRow['payment_type'] != "Token" and $inventoryhistoryRow['payment_type'] != "Part Payment") { ?>
								<div class="tooltip_col1">Name</div>
								<div class="tooltip_col2"><?php echo $validation->db_field_validate($registerRow['first_name'] . " " . $registerRow['last_name']); ?></div>
							<?php } ?>

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