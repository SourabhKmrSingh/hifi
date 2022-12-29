<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "siteVisit";

if(isset($_GET['mode']))
{
	$mode = $validation->urlstring_validate($_GET['mode']);
}
else
{
	$_SESSION['error_msg'] = "There is a problem. Please Try Again!";
	header("Location: site_visit_view.php");
	exit();
}

if($mode == "edit")
{
	$sitevisitid = $validation->urlstring_validate($_GET['sitevisitid']);
	$enquiryQueryResult = $db->view('*', 'mlm_site_visit', 'sitevisitid', "and regid='$regid' and sitevisitid = '$sitevisitid'");
	$sitevisitRow = $enquiryQueryResult['result'][0];
}
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
		<h1 CLASS="page-header"><?php if($mode == "insert") echo "Add New"; else echo "Update"; ?> Site Visit Request </h1>
	</div>
</div>

<form name="dataform" method="post" class="form-group" action="<?php 
												switch($mode)
												{
													case "insert" : echo "site_visit_form_inter.php?mode=$mode";
													break;
													
													case "edit" : echo "site_visit_form_inter.php?mode=$mode&sitevisitid=$sitevisitid";
													break;
													
													default : echo "site_visit_form_inter.php";
												}
												?>" enctype="multipart/form-data">

<input type="hidden" name="user_ip" value="<?php if($mode == 'edit') echo $validation->db_field_validate($sitevisitRow['user_ip']); ?>" />

<div class="form-rows-custom mt-3">
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="first_name">First Name *</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="first_name" id="first_name" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($sitevisitRow['first_name']); else echo $_SESSION['mlm_first_name']; ?>" required />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="last_name">Last Name</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="last_name" id="last_name" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($sitevisitRow['last_name']); else echo $_SESSION['mlm_last_name']; ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="email">Email *</label>
		</div>
		<div class="col-sm-9">
			<input type="email" name="email" id="email" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($sitevisitRow['email']); else echo $_SESSION['mlm_email']; ?>" required />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="mobile">Mobile No.</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="mobile" id="mobile" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($sitevisitRow['mobile']); else echo $_SESSION['mlm_mobile']; ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="site_visit_date">Date of Visit</label>
		</div>
		<div class="col-sm-9">
			<input type="date" min="<?php echo $createdate; ?>" name="site_visit_date" id="site_visit_date" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($sitevisitRow['site_visit_date']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="site_visit_time">Time of Visit</label>
		</div>
		<div class="col-sm-9">
			<input type="time" min="<?php echo $createdate; ?>" name="site_visit_time" id="site_visit_time" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($sitevisitRow['site_visit_time']); ?>" />
		</div>
	</div>

	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="total_person">Total Number of Person</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="total_person" id="total_person" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($sitevisitRow['total_person']); ?>" />
		</div>
	</div>

	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="pickup_location">Pickup location</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="pickup_location" id="pickup_location" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($sitevisitRow['pickup_location']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="remarks">Remarks</label>
		</div>
		<div class="col-sm-9">
			<textarea name="remarks" id="remarks" class="form-control"><?php if($mode == 'edit') echo $validation->db_field_validate($sitevisitRow['remarks']); ?></textarea>
		</div>
	</div>
	
	<?php if($mode == 'edit') { ?>
		<div class="row mb-3">
			<div class="col-sm-3">
				<label>Creation Date & Time</label>
			</div>
			<div class="col-sm-9">
				<?php if($sitevisitRow['createdate'] != "") { ?>
					<p class="text"><?php echo $validation->date_format_custom($sitevisitRow['createdate'])." at ".$validation->time_format_custom($sitevisitRow['createtime']); ?></p>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	
	<div class="row mt-4 mb-4">
		<div class="col-sm-12">
			<?php
			if($mode == "insert")
			{
			?>
				<button type="submit" class="btn  btn-sm mr-2 btn_submit"><i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;Add</button>
				<button type="reset" class="btn  btn-sm btn_delete"><i class="fas fa-sync-alt"></i>&nbsp;&nbsp;Reset</button>
			<?php
			}
			elseif($mode == "edit")
			{
			?>
				<button type="submit" name="submit" class="btn  btn-sm mr-2 btn_submit"><i class="fas fa-save"></i>&nbsp;&nbsp;Update</button>
			<?php
			}
			?>
		</div>
	</div>
</div>
</form>
</div>
</div>
</div>

</body>
</html>