<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "plot";

echo $validation->read_permission();

@$orderby = $validation->input_validate($_GET['orderby']);
@$order = $validation->input_validate($_GET['order']);

@$userid = $validation->input_validate($_GET['userid']);
@$projectid = $validation->input_validate($_GET['projectid']);
@$blockid = $validation->input_validate($_GET['blockid']);
@$title = strtolower($validation->input_validate($_GET['title']));
@$title_id = strtolower($validation->input_validate($_GET['title_id']));
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
if($blockid != "")
{
	$where_query .= " and blockid = '$blockid'";
}
if($title != "")
{
	$where_query .= " and LOWER(title) LIKE '%$title%'";
}
if($title_id != "")
{
	$where_query .= " and title_id = '$title_id'";
}
if($status != "")
{
	$where_query .= " and status = '$status'";
}
if($datefrom != "" and $dateto != "")
{
	$where_query .= " and createdate between '$datefrom' and '$dateto'";
}

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
	$orderby_final = "plotid desc";
}

$param1 = "title";
$param2 = "units";
$param3 = "createdate";
include_once("inc_sorting.php");

$table = "mlm_plots";
$id = "plotid";
$url_parameters = "&userid=$userid&projectid=$projectid&blockid=$blockid&title=$title&title_id=$title_id&status=$status&datefrom=$datefrom&dateto=$dateto";

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
		<h1 CLASS="page-header">Plots <?php if($_SESSION['per_write'] == "1"){ ?><a href="plot_form.php?mode=insert" class="btn mb_inline btn-sm btn_submit ml-3">Add New</a><?php } ?></h1>
	</div>
</div>

<form name="form_actions" method="POST" action="plot_actions.php" ENCTYPE="MULTIPART/FORM-DATA">
<div class="row">
	<div class="col-sm-12 mb-0">
		<div class="form-inline">
			<select NAME="bulk_actions" CLASS="form-control mb_inline mb-2" >
				<option VALUE="">Bulk Actions</option>
				<option VALUE="delete">Delete</option>
				<option VALUE="active">Status to Active</option>
				<option VALUE="inactive">Status to Inactive</option>
			</select>
			<button type="submit" class="btn  mb_inline btn-sm btn_submit mb-2 mr-4">Apply</button>
			
			<input type="text" name="title" class="form-control mb_inline mb-2" placeholder="Title" value="<?php echo $title; ?>" />
			<select NAME="status" CLASS="form-control mb_inline mb-2">
				<option VALUE="" <?php if($status=='') echo "selected"; ?>>Status</option>
				<option VALUE="active" <?php if($status=="active") echo "selected"; ?>>Active</option>
				<option VALUE="inactive" <?php if($status=="inactive") echo "selected"; ?>>Inactive</option>
			</select>
			<p class="pt-2">From&nbsp;</p> <input type="date" name="datefrom" class="form-control mb_inline mb-2" placeholder="From" value="<?php echo $datefrom; ?>" />
			<p class="pt-2">To&nbsp;</p> <input type="date" name="dateto" class="form-control mb_inline mb-2" placeholder="To" value="<?php echo $dateto; ?>" />
			<input type="submit" value="Filter" class="btn  mb_inline btn-sm btn_submit ml-sm-2 ml-md-0 mb-2 mr-1" />
			<a href="plot_view.php" class="btn  mb_inline btn-sm btn_delete ml-sm-2 ml-md-0 mb-2">Clear</a>
		</div>
	</div>
</div>

<div class="table-responsive">
<table class="table table-striped table-view" cellspacing="0" width="100%">
	<thead>
	<tr>
		<th class="check-row text-center"><input type="checkbox" name="select_all" onClick="selectall(this);" /></th>
		<th class="<?php echo $th_sort1." ".$th_order_cls1; ?>"><a href="plot_view.php?orderby=title&order=<?php echo $th_order1; echo $url_parameters; ?>"><span>Title</span> <span class="sorting-indicator"></span></a></th>
		<th>Author</th>
		<th>Amount</th>
		<th>Project</th>
		<th>Unit</th>
		<th>Size</th>
		<th>Block</th>
		<th>Status</th>
		<th class="<?php echo $th_sort3." ".$th_order_cls3; ?>"><a href="plot_view.php?orderby=createdate&order=<?php echo $th_order3.''.$url_parameters; ?>"><span>Date</span> <span class="sorting-indicator"></span></a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	if($data['num_rows'] >= 1)
	{
		foreach($data['result'] as $plotRow)
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
		<tr class="text-center has-row-actions">
			<td class="text-center" data-label=""><input type="checkbox" name="del_items[]" value="<?php echo $validation->db_field_validate($plotRow['plotid']); ?>"/></td>
			<td data-label="Title - ">
				<a href="plot_form.php?mode=edit&plotid=<?php echo $validation->db_field_validate($plotRow['plotid']); ?>" class="fw-500"><?php echo $validation->db_field_validate($plotRow['title']); ?></a>
				
				<div class="row row-actions">
					<div class="col-sm-12">
						<?php if($_SESSION['per_update'] == "1") { ?>
							<a href="plot_form.php?mode=edit&plotid=<?php echo $validation->db_field_validate($plotRow['plotid']); ?>">Edit</a>
							 | 
						<?php } ?>
						<?php if($_SESSION['per_delete'] == "1") { ?>
							<a href="plot_actions.php?q=del&plotid=<?php echo $validation->db_field_validate($plotRow['plotid']); ?>" onClick="return del();" class="delete">Delete</a>
						<?php } ?>
					</div>
				</div>
			</td>
			<td data-label="Author - "><a href="plot_view.php?userid=<?php echo $userid; ?>"><?php echo $validation->db_field_validate($userRow['display_name']); ?></a></td>
			<td data-label="Amount - ">&#8377;<?php echo $validation->price_format($plotRow['amount']); ?></td>
			<td data-label="Project - "><a href="plot_view.php?projectid=<?php echo $validation->db_field_validate($projectRow['projectid']); ?>"><?php echo $validation->db_field_validate($projectRow['title']); ?></a></td>
			<td data-label="Unit - "><?php echo $validation->db_field_validate($plotRow['units']); ?></td>
			<td data-label="Size - "><?php echo $validation->db_field_validate($plotRow['plot_size']); ?></td>
			<td data-label="Block - "><a href="plot_view.php?blockid=<?php echo $validation->db_field_validate($blockRow['blockid']); ?>"><?php echo $validation->db_field_validate($blockRow['title']); ?></a></td>
			<td data-label="Status - "><font color="<?php if($plotRow['status'] == "active") { echo "green"; } else { echo "red"; } ?>"><?php echo $validation->db_field_validate(ucfirst($plotRow['status'])); ?></font></td>
			<td class="date" data-label="Date - "><?php echo $validation->date_format_custom($plotRow['createdate']); ?> <br class="mb-hidden" />(<?php echo $validation->timecount("{$plotRow['createdate']} {$plotRow['createtime']}"); ?>)</td>
		</tr>
		<?php
		}
	}
	else
	{
	?>
		<tr class="text-center">
			<td class="text-center" colspan="8">No Record is Available!</td>
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