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
	header("Location: plot_inventory_view.php");
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

if($mode == "edit")
{
	$inventoryid = $validation->urlstring_validate($_GET['inventoryid']);
	$inventoryQueryResult = $db->view('*', 'mlm_plots_inventory', 'inventoryid', "and inventoryid = '$inventoryid'");
	$inventoryRow = $inventoryQueryResult['result'][0];

	$plotid = $inventoryRow['plotid'];
	
	$userid = $inventoryRow['userid'];
	$userQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid}'");
	$userRow = $userQueryResult['result'][0];
	
	$userid_updt = $inventoryRow['userid_updt'];
	$userupdtQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid_updt}'");
	$userupdtRow = $userupdtQueryResult['result'][0];
	
	$regid = $inventoryRow['regid'];
	$registerQueryResult = $db->view('*', 'mlm_registrations', 'regid', "and regid = '$regid'");
	$registerRow = $registerQueryResult['result'][0];
}

if(isset($_GET['q']))
{
	$q = $validation->urlstring_validate($_GET['q']);
	if($q == "imgdel")
	{
		$delresult = $media->filedeletion('mlm_plots_inventory', 'inventoryid', $inventoryid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
		if($delresult)
		{
			$_SESSION['success_msg'] = "Image has been deleted Successfully!!!";
			header("Location: plot_inventory_form.php?mode=edit&inventoryid=$inventoryid");
			exit();
		}
		else
		{
			$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
			header("Location: plot_inventory_form.php?mode=edit&inventoryid=$inventoryid");
			exit();
		}
	}
	
	if($q == "filedel")
	{
		$delresult = $media->filedeletion('mlm_plots_inventory', 'inventoryid', $inventoryid, 'fileName', FILE_LOC);
		if($delresult)
		{
			$_SESSION['success_msg'] = "File has been deleted Successfully!!!";
			header("Location: plot_inventory_form.php?mode=edit&inventoryid=$inventoryid");
			exit();
		}
		else
		{
			$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
			header("Location: plot_inventory_form.php?mode=edit&inventoryid=$inventoryid");
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
<script>
function fetch_block()
{
	$.ajax({
		type: 'post',
		url: 'fetch_block.php',
		data:
		{
			projectid: $("#projectid").val(),
			blockid: "<?php echo $inventoryRow['blockid']; ?>",
			mode: "<?php echo $mode; ?>"
		},
		success: function(result)
		{
			$("#block_area").html(result);
		}
	});
}

function fetch_plot()
{
	$.ajax({
		type: 'post',
		<?php if($mode == 'edit') { ?>
		url: 'fetch_plot.php',
		<?php } else { ?>
		url: 'fetch_plot2.php',
		<?php } ?>
		data:
		{
			blockid: $("#blockid").val(),
			plotid: "<?php echo $inventoryRow['plotid']; ?>",
			mode: "<?php echo $mode; ?>"
		},
		success: function(result)
		{
			$("#plot_area").html(result);
		}
	});
}


$(document).ready(function(){
	fetch_block();
	setTimeout(function(){
		fetch_plot();
	}, 1000);
	// setTimeout(fetch_plot(), 1000);
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
		<h1 CLASS="page-header"><?php if($mode == "insert") echo "Add New"; else echo "Update"; ?> Plot Inventory</h1>
	</div>
</div>

<form name="dataform" method="post" class="form-group" action="<?php 
												switch($mode)
												{
													case "insert" : echo "plot_inventory_form_inter.php?mode=$mode";
													break;
													
													case "edit" : echo "plot_inventory_form_inter.php?mode=$mode&inventoryid=$inventoryid";
													break;
													
													default : echo "plot_inventory_form_inter.php";
												}
												?>" enctype="multipart/form-data">

<input type="hidden" name="total_amount" value="<?php echo $inventoryRow['total_amount']; ?>" />
<input type="hidden" name="balance_amount" value="<?php echo $inventoryRow['balance_amount']; ?>" />
<input type="hidden" name="record_check" id="record_check" value="<?php echo $validation->db_field_validate($inventoryRow['record_check']); ?>" />
<input type="hidden" name="username" value="<?php echo $registerRow['username']; ?>" />
<input type="hidden" name="planid" value="<?php echo $registerRow['planid']; ?>" />
<input type="hidden" name="plotid" value="<?php echo $plotid; ?>" />
<input type="hidden" name="levelIncomeCheck" value="<?php echo $inventoryRow['levelIncomeCheck']; ?>" />

<div class="form-rows-custom mt-3">
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="projectid">Select Project *</label>
		</div>
		<div class="col-sm-9">
			<select NAME="projectid" CLASS="form-control" ID="projectid" onChange="fetch_block();" required >
				<option VALUE="">--select--</option>
				<?php
				$projectResult = $db->view('projectid,title', 'mlm_projects', 'projectid', "and status='active'", 'title asc');
				foreach($projectResult['result'] as $projectRow)
				{
				?>
					<option VALUE="<?php echo $validation->db_field_validate($projectRow['projectid']); ?>" <?php if($mode == 'edit') { if($projectRow['projectid'] == $inventoryRow['projectid']) echo "selected"; } ?>><?php echo $validation->db_field_validate($projectRow['title']); ?></option>
				<?php
				}
				?>
			</select>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="blockid">Select Block</label>
		</div>
		<div class="col-sm-9">
			<div id="block_area">
				<p class="text">No Data Available!</p>
			</div>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="plotid">Select Plot</label>
		</div>
		<div class="col-sm-9">
			<div id="plot_area">
				<p class="text">No Data Available!</p>
			</div>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="regid">Select User *</label>
		</div>
		<div class="col-sm-9">
			<select NAME="regid" CLASS="form-control" ID="regid" required >
				<option VALUE="">--select--</option>
				<?php
				$regResult = $db->view('regid,first_name,membership_id,last_name', 'mlm_registrations', 'regid', "and status='active'", 'membership_id asc');
				foreach($regResult['result'] as $regRow)
				{
				?>
					<option VALUE="<?php echo $validation->db_field_validate($regRow['regid']); ?>" <?php if($mode == 'edit') { if($regRow['regid'] == $inventoryRow['regid']) echo "selected"; } ?>><?php echo $validation->db_field_validate($regRow['membership_id'].' - '.$regRow['first_name'].' '.$regRow['last_name']); ?></option>
				<?php
				}
				?>
			</select>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="payment_type">Type *</label>
		</div>
		<div class="col-sm-9">
			<select name="payment_type" id="payment_type" class="form-control" required >
				<option value="" <?php if($mode == 'edit') { if($inventoryRow['payment_type'] == "") echo "selected"; } ?>>--select--</option>
				<option value="Token" <?php if($mode == 'edit') { if($inventoryRow['payment_type'] == "Token") echo "selected"; } ?>>Token</option>
				<option value="Part Payment" <?php if($mode == 'edit') { if($inventoryRow['payment_type'] == "Part Payment") echo "selected"; } ?>>Part Payment</option>
				<option value="Booked" <?php if($mode == 'edit') { if($inventoryRow['payment_type'] == "Booked") echo "selected"; } ?>>Booked</option>
				<option value="EMI" <?php if($mode == 'edit') { if($inventoryRow['payment_type'] == "EMI") echo "selected"; } ?>>EMI</option>
				<option value="Registered" <?php if($mode == 'edit') { if($inventoryRow['payment_type'] == "Registered") echo "selected"; } ?>>Registered</option>
			</select>
		</div>
	</div>


	<div class="row mb-3">
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
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="payment_mode">Mode of Payment *</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="payment_mode" id="payment_mode" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryRow['payment_mode']); ?>" required />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="amount">Amount *</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="amount" id="amount" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryRow['amount']); ?>" <?php if($mode == 'edit') echo "readonly"; ?> required />
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
	
	<div class="row mb-3">
		<div class="col-sm-12">
			<label for="description">Description</label>
		</div>
		<div class="col-sm-12">
			<button TYPE="button" CLASS="btn  btn-sm" id="image_model_button" onClick="document.getElementById('image_upper_text').style.display='none'; document.getElementById('userImage').value='';"><i class="fa fa-image" aria-hidden="true"></i> Add Image</button>
			<textarea id="description" name="description" class="tinymce"><?php if($mode == 'edit') echo $inventoryRow['description']; ?></textarea>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="imgName">Upload Image</label>
		</div>
		<div class="col-sm-9">
			<input type="file" name="imgName" id="imgName">
			<input type="hidden" name="old_imgName" id="old_imgName" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryRow['imgName']); ?>" />
			<?php if($mode == 'edit' and $inventoryRow['imgName'] != "") { ?>
				<div class="mt-2 links">
					<img src="<?php echo IMG_THUMB_LOC; echo $validation->db_field_validate($inventoryRow['imgName']); ?>" title="<?php echo $validation->db_field_validate($inventoryRow['imgName']); ?>" class="img-responsive mh-51" /><br>
					<a href="<?php echo IMG_MAIN_LOC; echo $validation->db_field_validate($inventoryRow['imgName']); ?>" target="_blank">Click to Download</a> | <a href="plot_inventory_form.php?mode=edit&inventoryid=<?php echo $inventoryid; ?>&q=imgdel" onClick="return del();">Delete</a>
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
			<input type="hidden" name="old_fileName" id="old_fileName" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryRow['fileName']); ?>" />
			<?php if($mode == 'edit' and $inventoryRow['fileName'] != "") { ?>
				<div class="mt-2 links">
					<a href="<?php echo FILE_LOC; echo $validation->db_field_validate($inventoryRow['fileName']); ?>" target="_blank">Click to Download</a> | <a href="plot_inventory_form.php?mode=edit&inventoryid=<?php echo $inventoryid; ?>&q=filedel" onClick="return del();">Delete</a>
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
				<option value="active" <?php if($mode == 'edit') { if($validation->db_field_validate($inventoryRow['status']) == "active") echo "selected"; } ?>>Active</option>
				<option value="inactive" <?php if($mode == 'edit') { if($validation->db_field_validate($inventoryRow['status']) == "inactive") echo "selected"; } ?>>Inactive</option>
			</select>
		</div>
	</div>
	
	<?php if($mode == 'edit') { ?>
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Author</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><a href="plot_inventory_view.php?userid=<?php echo $userid; ?>"><?php echo $validation->db_field_validate($userRow['display_name']); ?></a></p>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Author (Modified By)</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><a href="plot_inventory_view.php?userid=<?php echo $userid_updt; ?>"><?php echo $validation->db_field_validate($userupdtRow['display_name']); ?></a></p>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>User's IP Address</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><?php echo $validation->db_field_validate($inventoryRow['user_ip']); ?></p>
			<input type="hidden" name="user_ip" value="<?php if($mode == 'edit') echo $validation->db_field_validate($inventoryRow['user_ip']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Modification Date & Time</label>
		</div>
		<div class="col-sm-9">
			<?php if($inventoryRow['modifydate'] != "") { ?>
				<p class="text"><?php echo $validation->date_format_custom($inventoryRow['modifydate'])." at ".$validation->time_format_custom($inventoryRow['modifytime']); ?></p>
			<?php } ?>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Creation Date & Time</label>
		</div>
		<div class="col-sm-9">
			<?php if($inventoryRow['createdate'] != "") { ?>
				<p class="text"><?php echo $validation->date_format_custom($inventoryRow['createdate'])." at ".$validation->time_format_custom($inventoryRow['createtime']); ?></p>
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
					<a HREF="plot_inventory_actions.php?q=del&inventoryid=<?php echo $inventoryRow['inventoryid']; ?>" class="btn  btn-sm btn_delete" onClick="return del();"><i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</a>
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