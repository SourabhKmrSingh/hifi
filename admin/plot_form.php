<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "plot";

if(isset($_GET['mode']))
{
	$mode = $validation->urlstring_validate($_GET['mode']);
}
else
{
	$_SESSION['error_msg'] = "There is a problem. Please Try Again!";
	header("Location: plot_view.php");
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
	$plotid = $validation->urlstring_validate($_GET['plotid']);
	$plotQueryResult = $db->view('*', 'mlm_plots', 'plotid', "and plotid = '$plotid'");
	$plotRow = $plotQueryResult['result'][0];
	
	$userid = $plotRow['userid'];
	$userQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid}'");
	$userRow = $userQueryResult['result'][0];
	
	$userid_updt = $plotRow['userid_updt'];
	$userupdtQueryResult = $db->view("display_name", "mlm_users", "userid", "and userid='{$userid_updt}'");
	$userupdtRow = $userupdtQueryResult['result'][0];
}

if(isset($_GET['q']))
{
	$q = $validation->urlstring_validate($_GET['q']);
	if($q == "imgdel")
	{
		$delresult = $media->filedeletion('mlm_plots', 'plotid', $plotid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
		if($delresult)
		{
			$_SESSION['success_msg'] = "Image has been deleted Successfully!!!";
			header("Location: plot_form.php?mode=edit&plotid=$plotid");
			exit();
		}
		else
		{
			$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
			header("Location: plot_form.php?mode=edit&plotid=$plotid");
			exit();
		}
	}
	
	if($q == "filedel")
	{
		$delresult = $media->filedeletion('mlm_plots', 'plotid', $plotid, 'fileName', FILE_LOC);
		if($delresult)
		{
			$_SESSION['success_msg'] = "File has been deleted Successfully!!!";
			header("Location: plot_form.php?mode=edit&plotid=$plotid");
			exit();
		}
		else
		{
			$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
			header("Location: plot_form.php?mode=edit&plotid=$plotid");
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
			blockid: "<?php echo $plotRow['blockid']; ?>",
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
		<h1 CLASS="page-header"><?php if($mode == "insert") echo "Add New"; else echo "Update"; ?> Plot</h1>
	</div>
</div>

<form name="dataform" method="post" class="form-group" action="<?php 
												switch($mode)
												{
													case "insert" : echo "plot_form_inter.php?mode=$mode";
													break;
													
													case "edit" : echo "plot_form_inter.php?mode=$mode&plotid=$plotid";
													break;
													
													default : echo "plot_form_inter.php";
												}
												?>" enctype="multipart/form-data">

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
					<option VALUE="<?php echo $validation->db_field_validate($projectRow['projectid']); ?>" <?php if($mode == 'edit') { if($projectRow['projectid'] == $plotRow['projectid']) echo "selected"; } ?>><?php echo $validation->db_field_validate($projectRow['title']); ?></option>
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
			<label for="title"><strong>Title *</strong></label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="title" id="title" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['title']); ?>" required />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="title_id">Title ID <em>(Optional)</em></label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="title_id" id="title_id" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['title_id']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="plot_type">Plot Type</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="plot_type" id="plot_type" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['plot_type']); ?>" />
			<em class="d-block mt-1">Residential, Rental etc.</em>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="amount_sqryard">Amount (Per Square Yard)</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="amount_sqryard" id="amount_sqryard" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['amount_sqryard']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="plot_size">Plot Size</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="plot_size" id="plot_size" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['plot_size']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="amount">Plot Amount</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="amount" id="amount" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['amount']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="dimensions">Dimensions</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="dimensions" id="dimensions" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['dimensions']); ?>" />
			<em class="d-plot mt-1">15.0 X 34.0</em>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="units">Units</label>
		</div>
		<div class="col-sm-9">
			<input type="number" name="units" id="units" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['units']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="registry_number">Registry Number</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="registry_number" id="registry_number" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['registry_number']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="killa">Killa</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="killa" id="killa" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['killa']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="mutation">Mutation</label>
		</div>
		<div class="col-sm-9">
			<input type="text" name="mutation" id="mutation" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['mutation']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="registry_date">Registry Date</label>
		</div>
		<div class="col-sm-9">
			<input type="date" name="registry_date" id="registry_date" class="form-control" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['registry_date']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-12">
			<label for="description">Description</label>
		</div>
		<div class="col-sm-12">
			<button TYPE="button" CLASS="btn  btn-sm" id="image_model_button" onClick="document.getElementById('image_upper_text').style.display='none'; document.getElementById('userImage').value='';"><i class="fa fa-image" aria-hidden="true"></i> Add Image</button>
			<textarea id="description" name="description" class="tinymce"><?php if($mode == 'edit') echo $plotRow['description']; ?></textarea>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="imgName">Upload Image</label>
		</div>
		<div class="col-sm-9">
			<input type="file" name="imgName" id="imgName">
			<input type="hidden" name="old_imgName" id="old_imgName" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['imgName']); ?>" />
			<?php if($mode == 'edit' and $plotRow['imgName'] != "") { ?>
				<div class="mt-2 links">
					<img src="<?php echo IMG_THUMB_LOC; echo $validation->db_field_validate($plotRow['imgName']); ?>" title="<?php echo $validation->db_field_validate($plotRow['imgName']); ?>" class="img-responsive mh-51" /><br>
					<a href="<?php echo IMG_MAIN_LOC; echo $validation->db_field_validate($plotRow['imgName']); ?>" target="_blank">Click to Download</a> | <a href="plot_form.php?mode=edit&plotid=<?php echo $plotid; ?>&q=imgdel" onClick="return del();">Delete</a>
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
			<input type="hidden" name="old_fileName" id="old_fileName" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['fileName']); ?>" />
			<?php if($mode == 'edit' and $plotRow['fileName'] != "") { ?>
				<div class="mt-2 links">
					<a href="<?php echo FILE_LOC; echo $validation->db_field_validate($plotRow['fileName']); ?>" target="_blank">Click to Download</a> | <a href="plot_form.php?mode=edit&plotid=<?php echo $plotid; ?>&q=filedel" onClick="return del();">Delete</a>
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
			<input type="checkbox" name="priority" id="priority" <?php if($mode == 'edit') { if($plotRow['priority'] == "1") echo "checked"; } ?> />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="status">Status *</label>
		</div>
		<div class="col-sm-9">
			<select name="status" id="status" class="form-control" required >
				<option value="active" <?php if($mode == 'edit') { if($validation->db_field_validate($plotRow['status']) == "active") echo "selected"; } ?>>Active</option>
				<option value="inactive" <?php if($mode == 'edit') { if($validation->db_field_validate($plotRow['status']) == "inactive") echo "selected"; } ?>>Inactive</option>
			</select>
		</div>
	</div>
	
	<?php if($mode == 'edit') { ?>
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Author</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><a href="plot_view.php?userid=<?php echo $userid; ?>"><?php echo $validation->db_field_validate($userRow['display_name']); ?></a></p>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Author (Modified By)</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><a href="plot_view.php?userid=<?php echo $userid_updt; ?>"><?php echo $validation->db_field_validate($userupdtRow['display_name']); ?></a></p>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>User's IP Address</label>
		</div>
		<div class="col-sm-9">
			<p class="text"><?php echo $validation->db_field_validate($plotRow['user_ip']); ?></p>
			<input type="hidden" name="user_ip" value="<?php if($mode == 'edit') echo $validation->db_field_validate($plotRow['user_ip']); ?>" />
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Modification Date & Time</label>
		</div>
		<div class="col-sm-9">
			<?php if($plotRow['modifydate'] != "") { ?>
				<p class="text"><?php echo $validation->date_format_custom($plotRow['modifydate'])." at ".$validation->time_format_custom($plotRow['modifytime']); ?></p>
			<?php } ?>
		</div>
	</div>
	
	<div class="row mb-3">
		<div class="col-sm-3">
			<label>Creation Date & Time</label>
		</div>
		<div class="col-sm-9">
			<?php if($plotRow['createdate'] != "") { ?>
				<p class="text"><?php echo $validation->date_format_custom($plotRow['createdate'])." at ".$validation->time_format_custom($plotRow['createtime']); ?></p>
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
					<a HREF="plot_actions.php?q=del&plotid=<?php echo $plotRow['plotid']; ?>" class="btn  btn-sm btn_delete" onClick="return del();"><i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</a>
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