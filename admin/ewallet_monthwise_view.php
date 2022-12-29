<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "ewallet_monthwise";

echo $validation->read_permission();

@$orderby = $validation->input_validate($_GET['orderby']);
@$order = $validation->input_validate($_GET['order']);

@$userid = $validation->input_validate($_GET['userid']);
@$regid = $validation->input_validate($_GET['regid']);
@$membership_id = $validation->input_validate($_GET['membership_id']);
@$refno = $validation->input_validate($_GET['refno']);
@$type = $validation->input_validate($_GET['type']);
@$reason = $validation->input_validate($_GET['reason']);
@$status = strtolower($validation->input_validate($_GET['status']));
@$month = $validation->input_validate($_GET['month']);
if($month == "")
{
	$month = date('m');
}
@$year = $validation->input_validate($_GET['year']);
if($year == "")
{
	$year = date('Y');
}

$where_query = "";
if($userid != "")
{
	$where_query .= " and userid = '$userid'";
}
if($regid != "")
{
	$where_query .= " and regid = '$regid'";
}
if($membership_id != "")
{
	$where_query .= " and membership_id = '$membership_id'";
}
if($reason != "")
{
	$where_query .= " and reason = '$reason'";
}
if($month != "")
{
	$where_query .= " and MONTH(createdate) = '$month'";
}
if($year != "")
{
	$where_query .= " and YEAR(createdate) = '$year'";
}
$where_query .= " and type='credit'";

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
	$orderby_final = "ewalletid desc";
}

$param1 = "refno";
$param2 = "amount";
$param3 = "createdate";
include_once("inc_sorting.php");

$table = "mlm_ewallet";
$id = "ewalletid";
$groupby = "membership_id";
$url_parameters = "&userid=$userid&regid=$regid&membership_id=$membership_id&reason=$reason&status=$status&month=$month&year=$year";

$data = $pagination->main($table, $url_parameters, $where_query, $id, $orderby_final, $groupby);

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
		<h1 CLASS="page-header">Members E-Wallet</h1>
	</div>
</div>

<form name="form_actions" method="POST" action="ewallet_monthwise_actions.php" ENCTYPE="MULTIPART/FORM-DATA">
<div class="row">
	<div class="col-sm-12 mb-0">
		<div class="form-inline">
			<select NAME="bulk_actions" CLASS="form-control mb_inline mb-2" >
				<option VALUE="">Bulk Actions</option>
				<option VALUE="delete">Delete</option>
			</select>
			<button type="submit" class="btn  mb_inline btn-sm btn_submit mb-2 mr-4">Apply</button>
			
			<input type="text" name="membership_id" class="form-control mb_inline mb-2" placeholder="Membership ID" value="<?php echo $membership_id; ?>" />
			<input type="text" name="reason" class="form-control mb_inline mb-2" placeholder="Reason" value="<?php echo $reason; ?>" />
			<select NAME="month" CLASS="form-control mb_inline mb-2">
				<option VALUE="" <?php if($month=='') echo "selected"; ?>>Month</option>
				<?php for($i=1;$i<=12;$i++) { ?>
					<option VALUE="<?php echo sprintf("%02d", $i); ?>" <?php if($month==sprintf("%02d", $i)) echo "selected"; ?>><?php echo sprintf("%02d", $i); ?></option>
				<?php } ?>
			</select>
			<select NAME="year" CLASS="form-control mb_inline mb-2">
				<option VALUE="" <?php if($year=='') echo "selected"; ?>>Year</option>
				<?php for($i=date('Y');$i>date('Y')-3;$i--) { ?>
					<option VALUE="<?php echo sprintf("%04d", $i); ?>" <?php if($year==sprintf("%04d", $i)) echo "selected"; ?>><?php echo sprintf("%04d", $i); ?></option>
				<?php } ?>
			</select>
			<input type="submit" value="Filter" class="btn  mb_inline btn-sm btn_submit ml-sm-2 ml-md-0 mb-2 mr-1" />
			<a href="ewallet_monthwise_view.php" class="btn  mb_inline btn-sm btn_delete ml-sm-2 ml-md-0 mb-2">Clear</a>
		</div>
	</div>
</div>

<div class="table-responsive">
<table class="table table-striped table-view" cellspacing="0" width="100%">
	<thead>
	<tr>
		<th class="check-row text-center"><input type="checkbox" name="select_all" onClick="selectall(this);" /></th>
		<th>User</th>
		<th class="<?php echo $th_sort2." ".$th_order_cls2; ?>"><a href="ewallet_monthwise_view.php?orderby=amount&order=<?php echo $th_order2; echo $url_parameters; ?>"><span>Amount</span> <span class="sorting-indicator"></span></a></th>
		<th>Reason</th>
		<th>Description</th>
		<!--<th>Status</th>-->
		<th>Month & Year</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if($data['num_rows'] >= 1)
	{
		foreach($data['result'] as $ewalletRow)
		{
			$regid = $ewalletRow['regid'];
			
			$creditResult = $db->view("SUM(amount) as total_credit_amount", "mlm_ewallet", "regid", "{$where_query} and regid='{$regid}'");
			$creditRow = $creditResult['result'][0];
		?>
		<tr class="text-center has-row-actions">
			<td class="text-center" data-label=""><input type="checkbox" name="del_items[]" value="<?php echo $validation->db_field_validate($ewalletRow['ewalletid']); ?>"/></td>
			<td data-label="User - "><a href="ewallet_monthwise_view.php?regid=<?php echo $validation->db_field_validate($ewalletRow['regid']); ?>"><?php echo $validation->db_field_validate($ewalletRow['membership_id']); ?></a></td>
			<td data-label="Amount - ">&#8377;<?php echo $validation->price_format($creditRow['total_credit_amount']); ?></td>
			<td data-label="Reason - "><?php echo $validation->db_field_validate(ucwords($ewalletRow['reason'])); ?></td>
			<td data-label="Description - "><?php echo $validation->db_field_validate($ewalletRow['description']); ?></td>
			<!--<td data-label="Status - "><font color="<?php if($ewalletRow['status'] == "approved" || $ewalletRow['status'] == "fulfilled") { echo "green"; } else { echo "red"; } ?>"><?php echo $validation->db_field_validate(ucfirst($ewalletRow['status'])); ?></font></td>-->
			<td class="date" data-label="Month & Year - "><?php echo $month.' & '.$year; ?></td>
		</tr>
		<?php
		}
	}
	else
	{
	?>
		<tr class="text-center">
			<td class="text-center" colspan="6">No Record is Available!</td>
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