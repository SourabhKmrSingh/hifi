<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "plot_inventory_followup";

@$inventoryid = $validation->input_validate($_GET['inventoryid']);
if($inventoryid == "")
{
	$_SESSION['error_msg'] = "Please select Inventory!!!";
	header("Location: plot_inventory_view.php");
	exit();
}

@$orderby = $validation->input_validate($_GET['orderby']);
@$order = $validation->input_validate($_GET['order']);

@$userid = $validation->input_validate($_GET['userid']);
@$projectid = $validation->input_validate($_GET['projectid']);
@$plotid = $validation->input_validate($_GET['plotid']);
@$payment_type = $validation->input_validate($_GET['payment_type']);
@$datefrom = $validation->input_validate($_GET['datefrom']);
@$dateto = $validation->input_validate($_GET['dateto']);

$where_query = "";
if($userid != "")
{
	$where_query .= " and userid = '$userid'";
}
if($inventoryid != "")
{
	$where_query .= " and inventoryid = '$inventoryid'";
}
if($projectid != "")
{
	$where_query .= " and projectid = '$projectid'";
}
if($plotid != "")
{
	$where_query .= " and plotid = '$plotid'";
}
if($payment_type != "")
{
	$where_query .= " and payment_type = '$payment_type'";
}
if($datefrom != "" and $dateto != "")
{
	$where_query .= " and createdate between '$datefrom' and '$dateto'";
}
//$where_query .= " and regid='$regid'";

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
	$orderby_final = "historyid desc";
}

$param1 = "refno";
$param2 = "amount";
$param3 = "createdate";
include_once("inc_sorting.php");

$table = "mlm_plots_inventory_history";
$id = "historyid";
$url_parameters = "&userid=$userid&inventoryid=$inventoryid&projectid=$projectid&plotid=$plotid&payment_type=$payment_type&datefrom=$datefrom&dateto=$dateto";

$data = $pagination->main($table, $url_parameters, $where_query, $id, $orderby_final);

echo $validation->search_filter_enable();

$inventoryResult = $db->view("*", "mlm_plots_inventory", "inventoryid", "and inventoryid='{$inventoryid}'");
$inventoryRow = $inventoryResult['result'][0];

$projectid = $inventoryRow['projectid'];
$projectResult = $db->view("title,projectid", "mlm_projects", "projectid", "and projectid='{$projectid}'");
$projectRow = $projectResult['result'][0];

$plotid = $inventoryRow['plotid'];
$plotResult = $db->view("title,plotid", "mlm_plots", "plotid", "and plotid='{$plotid}'");
$plotRow = $plotResult['result'][0];
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
		<h1 CLASS="page-header">Plots Inventory History</h1>
	</div>
</div>

<div CLASS="row">
	<div CLASS="col-sm-4 mb-2 d-flex align-items-center justify-content-start">
		<h6>
			User - <?php echo $validation->db_field_validate($inventoryRow['membership_id']); ?>
			<br />
			Name: <?php echo $validation->db_field_validate($inventoryRow['name']); ?>
		</h6>
	</div>
	<div CLASS="col-sm-4 mb-2 d-flex align-items-center justify-content-center">
		<h6>
			Project - <?php echo $validation->db_field_validate($projectRow['title']); ?>
			<br />
			Plot - <?php echo $validation->db_field_validate($plotRow['title']); ?>
		</h6>
	</div>
	<div CLASS="col-sm-4 mb-2 d-flex align-items-center justify-content-end">
		<h6 class="text-right">
			Total Amount - &#8377;<?php echo $validation->price_format($inventoryRow['total_amount']); ?>
			<br />
			Balance Amount - &#8377;<?php echo $validation->price_format($inventoryRow['balance_amount']); ?>
		</h6>
	</div>
</div>

<form name="form_actions" method="POST" action="plot_inventory_history_actions.php?inventoryid=<?php echo $inventoryid; ?>" ENCTYPE="MULTIPART/FORM-DATA">
<div class="row">
	<div class="col-sm-12 mb-0">
		<div class="form-inline">
			<select NAME="payment_type" CLASS="form-control mb_inline mb-2">
				<option VALUE="" <?php if($payment_type=='') echo "selected"; ?>>Payment Type</option>
				<option value="Token" <?php if($payment_type == "Token") echo "selected"; ?>>Token</option>
				<option value="Part Payment" <?php if($payment_type == "Part Payment") echo "selected"; ?>>Part Payment</option>
				<option value="Booked" <?php if($payment_type == "Booked") echo "selected"; ?>>Booked</option>
				<option value="EMI" <?php if($payment_type == "EMI") echo "selected"; ?>>EMI</option>
			</select>
			<p class="pt-2">From&nbsp;</p> <input type="date" name="datefrom" class="form-control mb_inline mb-2" placeholder="From" value="<?php echo $datefrom; ?>" />
			<p class="pt-2">To&nbsp;</p> <input type="date" name="dateto" class="form-control mb_inline mb-2" placeholder="To" value="<?php echo $dateto; ?>" />
			<input type="submit" value="Filter" class="btn  mb_inline btn-sm btn_submit ml-sm-2 ml-md-0 mb-2 mr-1" />
			<a href="plot_inventory_history_view.php?inventoryid=<?php echo $inventoryid; ?>" class="btn  mb_inline btn-sm btn_delete ml-sm-2 ml-md-0 mb-2">Clear</a>
		</div>
	</div>
</div>

<div class="table-responsive">
<table class="table table-striped table-view" cellspacing="0" width="100%">
	<thead>
	<tr>
		<th class="<?php echo $th_sort1." ".$th_order_cls1; ?>"><a href="plot_inventory_history_view.php?orderby=refno&order=<?php echo $th_order1; echo $url_parameters; ?>"><span>Receipt No</span> <span class="sorting-indicator"></span></a></th>
		<th class="<?php echo $th_sort2." ".$th_order_cls2; ?>"><a href="plot_inventory_history_view.php?orderby=amount&order=<?php echo $th_order2; echo $url_parameters; ?>"><span>Amount</span> <span class="sorting-indicator"></span></a></th>
		<th>Cheque Details</th>
		<th>Bank Details</th>
		<th>Payment Type</th>
		<th>Description</th>
		<th class="<?php echo $th_sort3." ".$th_order_cls3; ?>"><a href="plot_inventory_history_view.php?orderby=createdate&order=<?php echo $th_order3.''.$url_parameters; ?>"><span>Date</span> <span class="sorting-indicator"></span></a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	if($data['num_rows'] >= 1)
	{
		foreach($data['result'] as $inventoryhistoryRow)
		{
			$userid = $inventoryhistoryRow['userid'];
			$userQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid}'");
			$userRow = $userQueryResult['result'][0];
		?>
			<tr class="text-center has-row-actions">
				<td data-label="Receipt No - ">
					<a class="fw-500"><?php echo $validation->db_field_validate($inventoryhistoryRow['refno']); ?></a>
				</td>
				<td data-label="Amount - ">&#8377;<?php echo $validation->price_format($inventoryhistoryRow['amount']); ?></td>
				<td data-label="Cheque Details - "><?php echo $validation->db_field_validate($inventoryhistoryRow['cheque_details']); ?></td>
				<td data-label="Bank Details - ">
					Bank Name: <?php echo $validation->db_field_validate($inventoryhistoryRow['bank_name']); ?><br />
					Account Number: <?php echo $validation->db_field_validate($inventoryhistoryRow['account_number']); ?><br />
					Bank Swift/IFSC Code: <?php echo $validation->db_field_validate($inventoryhistoryRow['ifsc_code']); ?><br />
					Account Name: <?php echo $validation->db_field_validate($inventoryhistoryRow['account_name']); ?><br />
				</td>
				<td data-label="Payment Type - "><?php echo $validation->db_field_validate($inventoryhistoryRow['payment_type']); ?></td>
				<td data-label="Description - "><?php echo $validation->db_field_validate($inventoryhistoryRow['description']); ?></td>
				<td class="date" data-label="Date - "><?php echo $validation->date_format_custom($inventoryhistoryRow['createdate']); ?> <br class="mb-hidden" />(<?php echo $validation->timecount("{$inventoryhistoryRow['createdate']} {$inventoryhistoryRow['createtime']}"); ?>)</td>
			</tr>
		<?php
		}
	}
	else
	{
	?>
		<tr class="text-center">
			<td class="text-center" colspan="7">No Record is Available!</td>
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