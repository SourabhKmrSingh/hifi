<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "plot_inventory_followup";

@$orderby = $validation->input_validate($_GET['orderby']);
@$order = $validation->input_validate($_GET['order']);

@$userid = $validation->input_validate($_GET['userid']);
@$projectid = $validation->input_validate($_GET['projectid']);
@$plotid = $validation->input_validate($_GET['plotid']);
@$status = strtolower($validation->input_validate($_GET['status']));
@$datefrom = $validation->input_validate($_GET['datefrom']);
@$dateto = $validation->input_validate($_GET['dateto']);

$where_query = "";
if($userid != "")
{
	$where_query .= " and userid = '$userid'";
}
if($projectid != "")
{
	$where_query .= " and projectid = '$projectid'";
}
if($plotid != "")
{
	$where_query .= " and plotid = '$plotid'";
}
if($status != "")
{
	$where_query .= " and status = '$status'";
}
if($datefrom != "" and $dateto != "")
{
	$where_query .= " and createdate between '$datefrom' and '$dateto'";
}
$where_query .= " and regid='$regid'";

if($orderby != "" and $order != "")
{
	$orderby_final = "{$orderby} {$order}";
	if($orderby == "createdate")
	{
		$orderby_final .= ", createtime {$order}";
	}
}
else
{
	$orderby_final = "inventoryid desc";
}

$param1 = "membership_id";
$param2 = "total_amount";
$param3 = "createdate";
include_once("inc_sorting.php");

$table = "mlm_plots_inventory";
$id = "inventoryid";
$url_parameters = "&userid=$userid&projectid=$projectid&plotid=$plotid&status=$status&datefrom=$datefrom&dateto=$dateto";

$data = $pagination->main($table, $url_parameters, $where_query, $id, $orderby_final);

echo $validation->search_filter_enable();
?>
<!DOCTYPE html>
<html LANG="en">
<head>
<?php include_once("inc_title.php"); ?>
<?php include_once("inc_files.php"); ?>
</head>
<body>
<div ID="wrapper">
<?php include_once("inc_header.php"); ?>
<div ID="page-wrapper">
<div CLASS="container-fluid">
<div CLASS="row">
	<div CLASS="col-lg-12">
		<h1 CLASS="page-header">Plots Inventories</h1>
	</div>
</div>

<form name="form_actions" method="POST" action="plot_inventory_actions.php" ENCTYPE="MULTIPART/FORM-DATA">
<div class="row">
	<div class="col-sm-12 mb-0">
		<div class="form-inline">
			<select NAME="status" CLASS="form-control mb_inline mb-2">
				<option VALUE="" <?php if($status=='') echo "selected"; ?>>Status</option>
				<option VALUE="active" <?php if($status=="active") echo "selected"; ?>>Active</option>
				<option VALUE="inactive" <?php if($status=="inactive") echo "selected"; ?>>Inactive</option>
			</select>
			<p class="pt-2">From&nbsp;</p> <input type="date" name="datefrom" class="form-control mb_inline mb-2" placeholder="From" value="<?php echo $datefrom; ?>" />
			<p class="pt-2">To&nbsp;</p> <input type="date" name="dateto" class="form-control mb_inline mb-2" placeholder="To" value="<?php echo $dateto; ?>" />
			<input type="submit" value="Filter" class="btn  mb_inline btn-sm btn_submit ml-sm-2 ml-md-0 mb-2 mr-1" />
			<a href="plot_inventory_view.php" class="btn  mb_inline btn-sm btn_delete ml-sm-2 ml-md-0 mb-2">Clear</a>
		</div>
	</div>
</div>

<div class="table-responsive">
<table class="table table-striped table-view" cellspacing="0" width="100%">
	<thead>
	<tr>
		<th class="<?php echo $th_sort1." ".$th_order_cls1; ?>"><a href="plot_inventory_view.php?orderby=membership_id&order=<?php echo $th_order1; echo $url_parameters; ?>"><span>User</span> <span class="sorting-indicator"></span></a></th>
		<th width="120">Name</th>
		<th>Sponsor ID</th>
		<th>Name</th>
		<th>Project</th>
		<th>Plot</th>
		<th class="<?php echo $th_sort2." ".$th_order_cls2; ?>"><a href="plot_inventory_view.php?orderby=total_amount&order=<?php echo $th_order2; echo $url_parameters; ?>"><span>Total Amount</span> <span class="sorting-indicator"></span></a></th>
		<th>Paid</th>
		<th>Balance</th>
		<th>Percentage</th>
		<th>Status</th>
		<th class="<?php echo $th_sort3." ".$th_order_cls3; ?>"><a href="plot_inventory_view.php?orderby=createdate&order=<?php echo $th_order3.''.$url_parameters; ?>"><span>Date</span> <span class="sorting-indicator"></span></a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	if($data['num_rows'] >= 1)
	{
		foreach($data['result'] as $inventoryRow)
		{
			$userid = $inventoryRow['userid'];
			$userQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid}'");
			$userRow = $userQueryResult['result'][0];
			
			$projectid = $inventoryRow['projectid'];
			$projectResult = $db->view("title,projectid", "mlm_projects", "projectid", "and projectid='{$projectid}'");
			$projectRow = $projectResult['result'][0];
			
			$plotid = $inventoryRow['plotid'];
			$plotResult = $db->view("title,plotid", "mlm_plots", "plotid", "and plotid='{$plotid}'");
			$plotRow = $plotResult['result'][0];
			
			$regid = $inventoryRow['regid'];
			$inventoryid = $inventoryRow['inventoryid'];
			
			$inventorytotalResult = $db->view("SUM(amount) as paid_amount", "mlm_plots_inventory_history", "historyid", "and status = 'active' and inventoryid='{$inventoryid}' and regid='{$regid}'");
			$inventorytotalRow = $inventorytotalResult['result'][0];
			$paid_amount = $inventorytotalRow['paid_amount'];
			
			$inventorystatuslResult = $db->view("payment_type", "mlm_plots_inventory_history", "historyid", "and status = 'active' and inventoryid='{$inventoryid}' and regid='{$regid}'", "historyid desc");
			$inventorystatusRow = $inventorystatuslResult['result'][0];
		?>
			<tr class="text-center has-row-actions">
				<td data-label="User - ">
					<a href="plot_inventory_history_view.php?inventoryid=<?php echo $validation->db_field_validate($inventoryRow['inventoryid']); ?>" class="fw-500"><?php echo $validation->db_field_validate($inventoryRow['membership_id']); ?></a>
					
					<div class="row row-actions">
						<div class="col-sm-12">
							<?php if($_SESSION['per_update'] == "1") { ?>
								<a href="plot_inventory_history_view.php?inventoryid=<?php echo $validation->db_field_validate($inventoryRow['inventoryid']); ?>">Payment History</a>
							<?php } ?>
						</div>
					</div>
				</td>
				<td data-label="Name - "><?php echo $validation->db_field_validate($inventoryRow['name']); ?></td>
				<td data-label="Sponsor ID - "><?php echo $validation->db_field_validate($inventoryRow['sponsor_id']); ?></td>
				<td data-label="Name - "><?php echo $validation->db_field_validate($inventoryRow['sponsor_name']); ?></td>
				<td data-label="Project - "><a href="plot_inventory_view.php?projectid=<?php echo $validation->db_field_validate($projectRow['projectid']); ?>"><?php echo $validation->db_field_validate($projectRow['title']); ?></a></td>
				<td data-label="Plot - "><a href="plot_inventory_view.php?plotid=<?php echo $validation->db_field_validate($plotRow['plotid']); ?>"><?php echo $validation->db_field_validate($plotRow['title']); ?></a></td>
				<td data-label="Total Amount - ">&#8377;<?php echo $validation->price_format($inventoryRow['total_amount']); ?></td>
				<td data-label="Paid - ">&#8377;<?php echo $validation->price_format($paid_amount); ?></td>
				<td data-label="Balance Amount - ">&#8377;<?php echo $validation->price_format($inventoryRow['balance_amount']); ?></td>
				<td data-label="Percentage - "><?php echo $validation->calculate_percentage($inventoryRow['total_amount'], $paid_amount); ?></td>
				<td data-label="Status - "><?php echo $validation->db_field_validate($inventorystatusRow['payment_type']); ?></td>
				<td class="date" data-label="Date - "><?php echo $validation->date_format_custom($inventoryRow['createdate']); ?> <br class="mb-hidden" />(<?php echo $validation->timecount("{$inventoryRow['createdate']} {$inventoryRow['createtime']}"); ?>)</td>
			</tr>
		<?php
		}
	}
	else
	{
	?>
		<tr class="text-center">
			<td class="text-center" colspan="12">No Record is Available!</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
</div>
</form>

<hr />
<?php echo $data['content']; ?>
<hr />
</div>
</div>
</div>
</body>
</html>