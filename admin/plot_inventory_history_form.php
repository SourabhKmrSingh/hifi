<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "plot_inventory_followup";

if(isset($_GET['mode']))
{
	$mode = $validation->urlstring_validate($_GET['mode']);
}
else
{
	$_SESSION['error_msg'] = "There is a problem. Please Try Again!";
	header("Location: plot_inventory_history_view.php");
	exit();
}

if($mode == "edit")
{
	echo $validation->update_permission();
}
else
{
	echo $validation->write_permission();
}

@$inventoryid = $validation->input_validate($_GET['inventoryid']);
if($inventoryid == "")
{
	$_SESSION['error_msg'] = "Please select Inventory!!!";
	header("Location: plot_inventory_view.php");
	exit();
}
$inventoryQueryResult = $db->view('*', 'mlm_plots_inventory', 'inventoryid', "and inventoryid = '$inventoryid'");
$inventoryRow = $inventoryQueryResult['result'][0];

$plotid = $inventoryRow['plotid'];
$plotQueryResult = $db->view('*', 'mlm_plots', 'plotid', "and plotid = '$plotid'");
$plotRow = $plotQueryResult['result'][0];

$regid = $inventoryRow['regid'];
$registerQueryResult = $db->view('*', 'mlm_registrations', 'regid', "and regid = '$regid'");
$registerRow = $registerQueryResult['result'][0];

if($mode == "edit")
{
	$historyid = $validation->urlstring_validate($_GET['historyid']);
	$inventoryhistoryResult = $db->view('*', 'mlm_plots_inventory_history', 'historyid', "and historyid = '$historyid'");
	$inventoryhistoryRow = $inventoryhistoryResult['result'][0];
	
	$userid = $inventoryhistoryRow['userid'];
	$userQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid}'");
	$userRow = $userQueryResult['result'][0];
	
	$userid_updt = $inventoryhistoryRow['userid_updt'];
	$userupdtQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid_updt}'");
	$userupdtRow = $userupdtQueryResult['result'][0];
}

if(isset($_GET['q']))
{
	$q = $validation->urlstring_validate($_GET['q']);
	if($q == "imgdel")
	{
		$delresult = $media->filedeletion('mlm_plots_inventory_history', 'historyid', $historyid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
		if($delresult)
		{
			$_SESSION['success_msg'] = "Image has been deleted Successfully!!!";
			header("Location: plot_inventory_history_form.php?mode=edit&historyid=$historyid");
			exit();
		}
		else
		{
			$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
			header("Location: plot_inventory_history_form.php?mode=edit&historyid=$historyid");
			exit();
		}
	}
	
	if($q == "filedel")
	{
		$delresult = $media->filedeletion('mlm_plots_inventory_history', 'historyid', $historyid, 'fileName', FILE_LOC);
		if($delresult)
		{
			$_SESSION['success_msg'] = "File has been deleted Successfully!!!";
			header("Location: plot_inventory_history_form.php?mode=edit&historyid=$historyid");
			exit();
		}
		else
		{
			$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
			header("Location: plot_inventory_history_form.php?mode=edit&historyid=$historyid");
			exit();
		}
	}
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
		<h1 CLASS="page-header"><?php if($mode == "insert") echo "Add New"; else echo "Update"; ?> Plot Inventory History</h1>
	</div>
</div>

<form name="dataform" method="post" class="form-group" action="<?php 
												switch($mode)
												{
													case "insert" : echo "plot_inventory_history_form_inter.php?mode=$mode";
													break;
													
													case "edit" : echo "plot_inventory_history_form_inter.php?mode=$mode&historyid=$historyid";
													break;
													
													default : echo "plot_inventory_history_form_inter.php";
												}
												?>" enctype="multipart/form-data">

<input type="hidden" name="inventoryid" value="<?php echo $inventoryid; ?>" />
<input type="hidden" name="total_amount" value="<?php echo $inventoryRow['total_amount']; ?>" />
<input type="hidden" name="balance_amount" value="<?php echo $inventoryRow['balance_amount']; ?>" />
<input type="hidden" name="plot_size" value="<?php echo $plotRow['plot_size']; ?>" />
<input type="hidden" name="units" value="<?php echo $plotRow['units']; ?>" />
<input type="hidden" name="regid" value="<?php echo $registerRow['regid']; ?>" />
<input type="hidden" name="name" value="<?php echo $registerRow['first_name'].' '.$registerRow['last_name']; ?>" />
<input type="hidden" name="refno" value="<?php  if($mode == 'edit') { echo $inventoryhistoryRow['refno']; }  ?>" />
<input type="hidden" name="refno_value" value="<?php  if($mode == 'edit') { echo $inventoryhistoryRow['refno_value']; }  ?>" />
<input type="hidden" name="membership_id" value="<?php echo $registerRow['membership_id']; ?>" />
<input type="hidden" name="sponsor_id" value="<?php echo $registerRow['sponsor_id']; ?>" />
<input type="hidden" name="sponsor_name" value="<?php echo $registerRow['sponsor_name']; ?>" />
<input type="hidden" name="bank_name" value="<?php echo $registerRow['bank_name']; ?>" />
<input type="hidden" name="account_number" value="<?php echo $registerRow['account_number']; ?>" />
<input type="hidden" name="ifsc_code" value="<?php echo $registerRow['ifsc_code']; ?>" />
<input type="hidden" name="account_name" value="<?php echo $registerRow['account_name']; ?>" />
<input type="hidden" name="planid" value="<?php echo $registerRow['planid']; ?>" />
<input type="hidden" name="username" value="<?php echo $registerRow['username']; ?>" />
<input type="hidden" name="record_check" id="record_check" value="<?php echo $validation->db_field_validate($inventoryRow['record_check']); ?>" />

<div class="form-rows-custom mt-3">
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="payment_type">Type *</label>
		</div>
		<div class="col-sm-9">
			<select name="payment_type" id="payment_type" class="form-control" required >
				<option value="" <?php if($mode == 'edit') { if($inventoryhistoryRow['payment_type'] == "") echo "selected"; } ?>>--select--</option>
				<option value="Token" <?php if($mode == 'edit') { if($inventoryhistoryRow['payment_type'] == "Token") echo "selected"; } ?>>Token</option>
				<option value="Part Payment" <?php if($mode == 'edit') { if($inventoryhistoryRow['payment_type'] == "Part Payment") echo "selected"; } ?>>Part Payment</option>
				<option value="Booked" <?php if($mode == 'edit') { if($inventoryhistoryRow['payment_type'] == "Booked") echo "selected"; } ?>>Booked</option>
				<option value="EMI" <?php if($mode == 'edit') { if($inventoryhistoryRow['payment_type'] == "EMI") echo "selected"; } ?>>EMI</option>
				<option value="Registered" <?php if($mode == 'edit') { if($inventoryhistoryRow['payment_type'] == "Registered") echo "selected"; } ?>>Registered</option>
			</select>
		</div>
	</div>
	
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="payment_mode">Mode of Payment *</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="payment_mode" id="payment_mode" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryhistoryRow['payment_mode']); ?>" required />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="amount">Amount *</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="amount" id="amount" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryhistoryRow['amount']); ?>" <?php if($inventoryhistoryRow['amount'] != "") echo "readonly"; ?> />
			<em>You can enter maximum of &#8377;<?php echo $validation->price_format($inventoryRow['balance_amount']); ?></em>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="cheque_details">Cheque Details</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="cheque_details" id="cheque_details" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryhistoryRow['cheque_details']); ?>" />
		</div>
	</div>

	<!-- <div class="row mb-3">
		<div class="col-sm-3">
			<label for="payment_process">Payment Process *</label>
		</div>
		<div class="col-sm-9">
			<?php if($inventoryRow['payment_process'] == ""){?>
			<select name="payment_process" id="payment_process" class="form-control">
				<option value="" <?php if($mode == 'edit') { if($inventoryRow['payment_process'] == "") echo "selected"; } ?>>--select--</option>
				<option value="Closed" <?php if($mode == 'edit') { if($inventoryRow['payment_process'] == "Closed") echo "selected"; } ?>>Closed</option>
			</select>
			<?php }else if($inventoryRow['payment_process'] == 'Closed'){?>
				<input type="text" readonly class='form-control' name='payment_process' value='Closed'>
			<?php }?>
		</div>
	</div> -->
	
	<div class="row mb-3">
		<div class="col-sm-12">
			<label for="description">Description</label>
		</div>
		<div class="col-sm-12">
			<button TYPE="button" CLASS="btn  btn-sm" id="image_model_button" onClick="document.getElementById('image_upper_text').style.display='none'; document.getElementById('userImage').value='';"><i class="fa fa-image" aria-hidden="true"></i> Add Image</button>
			<textarea id="description" name="description" class="tinymce"><?php if($mode == 'edit') echo $inventoryhistoryRow['description']; ?></textarea>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="imgName">Upload Image</label>
		</div>
		<div class="col-sm-9">
			<input type="file" name="imgName" id="imgName">
			<input type="hidden" name="old_imgName" id="old_imgName" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryhistoryRow['imgName']); ?>" />
			<?php if($mode == 'edit' and $inventoryhistoryRow['imgName'] != "") { ?>
				<div class="mt-2 links">
					<img src="<?php echo IMG_THUMB_LOC; echo $validation->db_field_validate($inventoryhistoryRow['imgName']); ?>" title="<?php echo $validation->db_field_validate($inventoryhistoryRow['imgName']); ?>" class="img-responsive mh-51" /><br>
					<a href="<?php echo IMG_MAIN_LOC; echo $validation->db_field_validate($inventoryhistoryRow['imgName']); ?>" target="_blank">Click to Download</a> | <a href="plot_inventory_history_form.php?mode=edit&historyid=<?php echo $historyid; ?>&q=imgdel" onClick="return del();">Delete</a>
				</div>
			<?php } ?>
			<em class="d-block mt-1">File should be Image and size under <?php echo $validation->convertToReadableSize($configRow['image_maxsize']); ?><br>Image extension should be .jpg, .jpeg, .png, .gif</em>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="fileName">Upload File</label>
		</div>
		<div class="col-sm-9">
			<input type="file" name="fileName" id="fileName">
			<input type="hidden" name="old_fileName" id="old_fileName" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryhistoryRow['fileName']); ?>" />
			<?php if($mode == 'edit' and $inventoryhistoryRow['fileName'] != "") { ?>
				<div class="mt-2 links">
					<a href="<?php echo FILE_LOC; echo $validation->db_field_validate($inventoryhistoryRow['fileName']); ?>" target="_blank">Click to Download</a> | <a href="plot_inventory_history_form.php?mode=edit&historyid=<?php echo $historyid; ?>&q=filedel" onClick="return del();">Delete</a>
				</div>
			<?php } ?>
			<em class="d-block mt-1">File size under <?php echo $validation->convertToReadableSize($configRow['file_maxsize']); ?><br>File extension should be .pdf, .docx, .doc, .xlsx, .csv, .zip</em>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="status">Status *</label>
		</div>
		<div class="col-sm-9">
			<select name="status" id="status" class="form-control" required >
				<option value="active" <?php if($mode == 'edit') { if($validation->db_field_validate($inventoryhistoryRow['status']) == "active") echo "selected"; } ?>>Active</option>
				<option value="inactive" <?php if($mode == 'edit') { if($validation->db_field_validate($inventoryhistoryRow['status']) == "inactive") echo "selected"; } ?>>Inactive</option>
			</select>
		</div>
	</div>
	
	<?php if($mode == 'edit') { ?>
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Author</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><a href="plot_inventory_history_view.php?userid=<?php echo $userid; ?>"><?php echo $validation->db_field_validate($userRow['display_name']); ?></a></p>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Author (Modified By)</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><a href="plot_inventory_history_view.php?userid=<?php echo $userid_updt; ?>"><?php echo $validation->db_field_validate($userupdtRow['display_name']); ?></a></p>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>User's IP Address</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><?php echo $validation->db_field_validate($inventoryhistoryRow['user_ip']); ?></p>
			<input type="hidden" name="user_ip" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryhistoryRow['user_ip']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Modification Date & Time</label>
		</div>
		<div class="col-sm-9">
			<?php if($inventoryhistoryRow['modifydate'] != "") { ?>
				<p class="text"><?php echo $validation->date_format_custom($inventoryhistoryRow['modifydate'])." at ".$validation->time_format_custom($inventoryhistoryRow['modifytime']); ?></p>
			<?php } ?>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Creation Date & Time</label>
		</div>
		<div class="col-sm-9">
			<?php if($inventoryhistoryRow['createdate'] != "") { ?>
				<p class="text"><?php echo $validation->date_format_custom($inventoryhistoryRow['createdate'])." at ".$validation->time_format_custom($inventoryhistoryRow['createtime']); ?></p>
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
				<?php if($_SESSION['per_delete'] == "1") { ?>
					<a HREF="plot_inventory_history_actions.php?q=del&historyid=<?php echo $inventoryhistoryRow['historyid']; ?>&inventoryid=<?php echo $validation->db_field_validate($inventoryhistoryRow['inventoryid']); ?>" class="btn  btn-sm btn_delete" onClick="return del();"><i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</a>
				<?php } ?>
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

<div ID="image_model" CLASS="modal">
	<div CLASS="modal-content">
		<div class="row">
			<div class="col-10">
				<div class="image_modal_heading"><i class="fa fa-image" aria-hidden="true"></i> Upload Image</div>
			</div>
			<div class="col-2">
				<div CLASS="image_close_button">&times;</div>
			</div>
		</div>
		<div STYLE="background:; padding:3%;">
			<p align="center">Select/Upload files from your local machine to server.</p>
			<div ID="drop-area"><p CLASS="drop-text" STYLE="margin-top:50px;">
				<p class="image_upper_text" id="image_upper_text"><i class="fas fa-check" aria-hidden="true" style="color: #0BC414;"></i> Your Image has been Uploaded. Upload more pictures!!!</p>
				<img src="images/Loading_icon.gif" class="image_model_loader" style="display:none;" />
				<p class="image_lower_text"><form name="uploadForm" id="uploadForm">
				<input type="file" name="userImage" class="d-none" onChange="uploadimage(this);" id="userImage">
				<label for="userImage" class="file_design"><i class="fa fa-image" aria-hidden="true"></i> Select File</label>&nbsp; or Drag it Here
				</form></p>
			</p></div>
			<br>
			<button TYPE="BUTTON" ID="image_close" CLASS="btn btn-success btn-sm">Done</button>
		</div>
	</div>
</div>

</body>
</html>