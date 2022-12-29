<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "map";

$where_query = "";
$orderby_final = "mapid desc";

$table = "mlm_maps";
$id = "mapid";
$url_parameters = "";

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
		<h1 CLASS="page-header">Maps</h1>
	</div>
</div>

<div class="table-responsive">
<table class="table table-striped table-view" cellspacing="0" width="100%">
	<thead>
	<tr>
		<th>Title</th>
		<th>Files</th>
		<th>Description</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if($data['num_rows'] >= 1)
	{
		foreach($data['result'] as $mapRow)
		{
			$userid = $mapRow['userid'];
			$userQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid}'");
			$userRow = $userQueryResult['result'][0];
		?>
		<tr class="text-center has-row-actions">
			<td data-label="Title - "><?php echo $validation->db_field_validate($mapRow['title']); ?></td>
			<td data-label="Files - ">
				<?php if($mapRow['imgName'] != "") { ?>
					Image: <a href="<?php echo IMG_MAIN_LOC.''.$mapRow['imgName']; ?>" target="_blank">Click to Download</a>
					<br />
				<?php } ?>
				<?php if($mapRow['fileName'] != "") { ?>
					File: <a href="<?php echo IMG_MAIN_LOC.''.$mapRow['fileName']; ?>" target="_blank">Click to Download</a>
					<br />
				<?php } ?>
			</td>
			<td data-label="Description - "><?php echo $validation->db_field_validate($mapRow['description']); ?></td>
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

<hr />
<?php echo $data['content']; ?>
<hr />
</div>
</div>
</div>
</body>
</html>