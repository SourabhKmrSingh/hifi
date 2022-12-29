<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "reward";

if(isset($_GET['mode']))
{
	$mode = $validation->urlstring_validate($_GET['mode']);
}
else
{
	$_SESSION['error_msg'] = "There is a problem. Please Try Again!";
	header("Location: reward_view.php");
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
	$rewardid = $validation->urlstring_validate($_GET['rewardid']);
	$rewardQueryResult = $db->view('*', 'mlm_rewards', 'rewardid', "and rewardid = '$rewardid'");
	$rewardRow = $rewardQueryResult['result'][0];
	
	$userid = $rewardRow['userid'];
	$userQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid}'");
	$userRow = $userQueryResult['result'][0];
	
	$userid_updt = $rewardRow['userid_updt'];
	$userupdtQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid_updt}'");
	$userupdtRow = $userupdtQueryResult['result'][0];
}
else
{
	$max_order = $db->get_maxorder('mlm_rewards') + 1;
}

if(isset($_GET['q']))
{
	$q = $validation->urlstring_validate($_GET['q']);
	if($q == "imgdel")
	{
		$delresult = $media->filedeletion('mlm_rewards', 'rewardid', $rewardid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
		if($delresult)
		{
			$_SESSION['success_msg'] = "Image has been deleted Successfully!!!";
			header("Location: reward_form.php?mode=edit&rewardid=$rewardid");
			exit();
		}
		else
		{
			$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
			header("Location: reward_form.php?mode=edit&rewardid=$rewardid");
			exit();
		}
	}
	
	if($q == "filedel")
	{
		$delresult = $media->filedeletion('mlm_rewards', 'rewardid', $rewardid, 'fileName', FILE_LOC);
		if($delresult)
		{
			$_SESSION['success_msg'] = "File has been deleted Successfully!!!";
			header("Location: reward_form.php?mode=edit&rewardid=$rewardid");
			exit();
		}
		else
		{
			$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
			header("Location: reward_form.php?mode=edit&rewardid=$rewardid");
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
		<h1 CLASS="page-header"><?php if($mode == "insert") echo "Add New"; else echo "Update"; ?> Reward</h1>
	</div>
</div>

<form name="dataform" method="post" class="form-group" action="<?php 
												switch($mode)
												{
													case "insert" : echo "reward_form_inter.php?mode=$mode";
													break;
													
													case "edit" : echo "reward_form_inter.php?mode=$mode&rewardid=$rewardid";
													break;
													
													default : echo "reward_form_inter.php";
												}
												?>" enctype="multipart/form-data">

<div class="form-rows-custom mt-3">
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="planid">Plan *</label>
		</div>
		<div class="col-sm-9">
			<select NAME="planid" CLASS="form-control" ID="planid" required >
				<option VALUE="">--select--</option>
				<?php
				$planQueryResult = $db->view('planid,title', 'mlm_plans', 'planid', "and status='active'", 'title asc');
				foreach($planQueryResult['result'] as $planRow)
				{
				?>
					<option VALUE="<?php echo $validation->db_field_validate($planRow['planid']); ?>" <?php if($mode == 'edit') { if($planRow['planid'] == $rewardRow['planid']) echo "selected"; } else { echo "selected"; } ?>><?php echo $validation->db_field_validate($planRow['title']); ?></option>
				<?php
				}
				?>
			</select>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="title"><strong>Title *</strong></label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="title" id="title" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['title']); ?>" required />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="direct_sale">Direct Members Sale</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="direct_sale" id="direct_sale" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['direct_sale']); ?>" step="any" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="group_sale">Group Members Sale</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="group_sale" id="group_sale" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['group_sale']); ?>" step="any" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="total_sale">Total Members Sale</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="total_sale" id="total_sale" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['total_sale']); ?>" step="any" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="incentive">Incentive</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="incentive" id="incentive" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['incentive']); ?>" step="any" />
			<em>per unit</em>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="condition_rewardid">Condition</label>
		</div>
		<div class="col-sm-9">
			<select NAME="condition_rewardid" CLASS="form-control" ID="condition_rewardid" >
				<option VALUE="">--select--</option>
				<?php
				$conditionRewardResult = $db->view('rewardid,title', 'mlm_rewards', 'rewardid', "and status='active'", 'title asc');
				foreach($conditionRewardResult['result'] as $conditionRewardRow)
				{
				?>
					<option VALUE="<?php echo $validation->db_field_validate($conditionRewardRow['rewardid']); ?>" <?php if($mode == 'edit') { if($conditionRewardRow['rewardid'] == $rewardRow['condition_rewardid']) echo "selected"; } ?>><?php echo $validation->db_field_validate($conditionRewardRow['title']); ?></option>
				<?php
				}
				?>
			</select>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="condition_members">Condition Number of Members</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="condition_members" id="condition_members" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['condition_members']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="condition_legs">Condition Leg(s)</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="condition_legs" id="condition_legs" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['condition_legs']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="salary">Salary</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="salary" id="salary" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['salary']); ?>" />
			<em>per month</em>
		</div>
	</div>

<!-- <div class="row mb-3">
		<div class="col-sm-3">
			<label for="maintian">Maintain (Unit)</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="maintian" id="maintian" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['maintian']); ?>" />
		</div>
	</div> -->
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="order_custom">Order</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="order_custom" id="order_custom" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['order_custom']); else echo $max_order; ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-12">
			<label for="description">Description</label>
		</div>
		<div class="col-sm-12">
			<button TYPE="button" CLASS="btn  btn-sm" id="image_model_button" onClick="document.getElementById('image_upper_text').style.display='none'; document.getElementById('userImage').value='';"><i class="fa fa-image" aria-hidden="true"></i> Add Image</button>
			<textarea id="description" name="description" class="tinymce"><?php if($mode == 'edit') echo $rewardRow['description']; ?></textarea>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="imgName">Upload Image</label>
		</div>
		<div class="col-sm-9">
			<input type="file" name="imgName" id="imgName">
			<input type="hidden" name="old_imgName" id="old_imgName" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['imgName']); ?>" />
			<?php if($mode == 'edit' and $rewardRow['imgName'] != "") { ?>
				<div class="mt-2 links">
					<img src="<?php echo IMG_THUMB_LOC; echo $validation->db_field_validate($rewardRow['imgName']); ?>" title="<?php echo $validation->db_field_validate($rewardRow['imgName']); ?>" class="img-responsive mh-51" /><br>
					<a href="<?php echo IMG_MAIN_LOC; echo $validation->db_field_validate($rewardRow['imgName']); ?>" target="_blank">Click to Download</a> | <a href="reward_form.php?mode=edit&rewardid=<?php echo $rewardid; ?>&q=imgdel" onClick="return del();">Delete</a>
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
			<input type="hidden" name="old_fileName" id="old_fileName" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['fileName']); ?>" />
			<?php if($mode == 'edit' and $rewardRow['fileName'] != "") { ?>
				<div class="mt-2 links">
					<a href="<?php echo FILE_LOC; echo $validation->db_field_validate($rewardRow['fileName']); ?>" target="_blank">Click to Download</a> | <a href="reward_form.php?mode=edit&rewardid=<?php echo $rewardid; ?>&q=filedel" onClick="return del();">Delete</a>
				</div>
			<?php } ?>
			<em class="d-block mt-1">File size under <?php echo $validation->convertToReadableSize($configRow['file_maxsize']); ?><br>File extension should be .pdf, .docx, .doc, .xlsx, .csv, .zip</em>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="priority">Priority ?</label>
		</div>
		<div class="col-sm-9">
			<input type="checkbox" name="priority" id="priority" <?php if($mode == 'edit') { if($rewardRow['priority'] == "1") echo "checked"; } ?> />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="status">Status *</label>
		</div>
		<div class="col-sm-9">
			<select name="status" id="status" class="form-control" required >
				<option value="active" <?php if($mode == 'edit') { if($validation->db_field_validate($rewardRow['status']) == "active") echo "selected"; } ?>>Active</option>
				<option value="inactive" <?php if($mode == 'edit') { if($validation->db_field_validate($rewardRow['status']) == "inactive") echo "selected"; } ?>>Inactive</option>
			</select>
		</div>
	</div>
	
	<?php if($mode == 'edit') { ?>
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Author</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><a href="reward_view.php?userid=<?php echo $userid; ?>"><?php echo $validation->db_field_validate($userRow['display_name']); ?></a></p>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Author (Modified By)</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><a href="reward_view.php?userid=<?php echo $userid_updt; ?>"><?php echo $validation->db_field_validate($userupdtRow['display_name']); ?></a></p>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>User's IP Address</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><?php echo $validation->db_field_validate($rewardRow['user_ip']); ?></p>
			<input type="hidden" name="user_ip" value="<?php if($mode == 'edit') echo $validation->db_field_validate($rewardRow['user_ip']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Modification Date & Time</label>
		</div>
		<div class="col-sm-9">
			<?php if($rewardRow['modifydate'] != "") { ?>
				<p class="text"><?php echo $validation->date_format_custom($rewardRow['modifydate'])." at ".$validation->time_format_custom($rewardRow['modifytime']); ?></p>
			<?php } ?>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Creation Date & Time</label>
		</div>
		<div class="col-sm-9">
			<?php if($rewardRow['createdate'] != "") { ?>
				<p class="text"><?php echo $validation->date_format_custom($rewardRow['createdate'])." at ".$validation->time_format_custom($rewardRow['createtime']); ?></p>
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
					<a HREF="reward_actions.php?q=del&rewardid=<?php echo $rewardRow['rewardid']; ?>" class="btn  btn-sm btn_delete" onClick="return del();"><i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</a>
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