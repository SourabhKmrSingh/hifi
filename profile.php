<?php

include_once("inc_config.php");

include_once("login_user_check.php");



$_SESSION['active_menu'] = "profile";



$registerQueryResult = $db->view('*', 'mlm_registrations', 'regid', "and regid = '$regid'");

$registerRow = $registerQueryResult['result'][0];



if(isset($_GET['q']))

{

	$q = $validation->urlstring_validate($_GET['q']);

	if($q == "imgdel")

	{

		$delresult = $media->filedeletion('mlm_registrations', 'regid', $regid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);

		if($delresult)

		{

			$_SESSION['success_msg'] = "Image has been deleted Successfully!!!";

			header("Location: profile.php?mode=edit&regid=$regid");

			exit();

		}

		else

		{

			$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";

			header("Location: profile.php?mode=edit&regid=$regid");

			exit();

		}

	}

	if($q == "imgdel2")
	{
		$delresult2 = $media->filedeletion('mlm_registrations', 'regid', $regid, 'signature', IMG_MAIN_LOC, IMG_THUMB_LOC);
		if($delresult2)
		{
			$_SESSION['success_msg'] = "Image has been deleted Successfully!!!";
			
			header("Location: profile.php?mode=edit&regid=$regid");
			exit();
		}
		else
		{
			$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
			header("Location: profile.php?mode=edit&regid=$regid");
			exit();
		}
	}


	if (isset($_GET['q'])) {
		$q = $validation->urlstring_validate($_GET['q']);
		if ($q == "imgdel") {
			$delresult = $media->filedeletion('mlm_registrations', 'regid', $regid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
			if ($delresult) {
				$_SESSION['success_msg'] = "Image has been deleted Successfully!!!";
				header("Location: profile.php?mode=edit&regid=$regid");
				exit();
			} else {
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: profile.php?mode=edit&regid=$regid");
				exit();
			}
		}
	}
	
	
	
	if(isset($_GET['q']))
	{
		$q = $validation->urlstring_validate($_GET['q']);
		if($q == "kycdel")
		{
			$kycdoc = $validation->urlstring_validate($_GET['kycdoc']);
			$delresult = $media->multiple_filedeletion('mlm_registrations', 'regid', $regid, 'kycdoc', IMG_MAIN_LOC, IMG_THUMB_LOC, $kycdoc);
			if($delresult)
			{
				$_SESSION['success_msg'] = "Aadhaar Image has been deleted Successfully!!!";
				header("Location: profile.php");
				exit();
			}
			else
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: profile.php");
				exit();
			}
		}
	
		if($q == "panImage")
		{
			$panImage = $validation->urlstring_validate($_GET['panImage']);
			$delresult = $media->multiple_filedeletion('mlm_registrations', 'regid', $regid, 'panImage', IMG_MAIN_LOC, IMG_THUMB_LOC, $panImage);
			if($delresult)
			{
				$_SESSION['success_msg'] = "Pan Card Image has been deleted Successfully!!!";
				header("Location: profile.php");
				exit();
			}
			else
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: profile.php");
				exit();
			}
		}

		if($q == "bankdel")
		{
			$bankdoc = $validation->urlstring_validate($_GET['bankdoc']);
			$delresult = $media->multiple_filedeletion('mlm_registrations', 'regid', $regid, 'bankdoc', IMG_MAIN_LOC, IMG_THUMB_LOC, $bankdoc);
			if($delresult)
			{
				$_SESSION['success_msg'] = "Bank Image has been deleted Successfully!!!";
				header("Location: profile.php");
				exit();
			}
			else
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: profile.php");
				exit();
			}
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

function passwordmatch()

{

	if($("#password").val() != $("#confirm_password").val())

	{

		alert("Password and Confirm Password should be Same!");

		$("#password").val("");

		$("#confirm_password").val("");

	}

}

</script>

</head>

<body>

<div ID="wrapper">

<?php include_once("inc_header.php"); ?>

<div ID="page-wrapper">

<div CLASS="container-fluid">

<div CLASS="row">

	<div CLASS="col-lg-12">

		<h1 CLASS="page-header">Profile</h1>

	</div>

</div>



<form name="dataform" method="post" class="form-group" action="profile_inter.php" enctype="multipart/form-data">

<input type="hidden" name="user_ip" value="<?php echo $validation->db_field_validate($registerRow['user_ip']); ?>" />

<div class="form-rows-custom mt-3">

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Membership ID</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['membership_id']); ?></p>

		</div>

	</div>

	<!--<div class="row mb-3">

		<div class="col-sm-3">

			<label>Referral Link</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><a href="<?php echo BASE_URL.'register'.SUFFIX.'?id='.$validation->db_field_validate($registerRow['membership_id']); ?>" target="_blank"><?php echo BASE_URL.'register'.SUFFIX.'?id='.$validation->db_field_validate($registerRow['membership_id']); ?></a></p>

		</div>

	</div>-->

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Sponsor's ID</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['sponsor_id']); ?></p>

		</div>

	</div>

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Sponsor's Name</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['sponsor_name']); ?></p>

		</div>

	</div>

	

	<!-- <div class="row mb-3">

		<div class="col-sm-3">

			<label>Wallet Balance</label>

		</div>

		<div class="col-sm-9">

			<p class="text">&#8377;<?php echo $validation->price_format($registerRow['wallet_money']); ?></p>

		</div>

	</div>

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Total Credits</label>

		</div>

		<div class="col-sm-9">

			<p class="text">&#8377;<?php echo $validation->price_format($registerRow['wallet_total']); ?></p>

		</div>

	</div>

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Total Debits</label>

		</div>

		<div class="col-sm-9">

			<p class="text">&#8377;<?php echo $validation->price_format($registerRow['total_debit']); ?></p>

		</div>

	</div>

	

	<div class="row mb-1">

		<div class="col-sm-3">

			<label>Total Members</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['members']); ?></p>

		</div>

	</div>

	<div class="row mb-1">

		<div class="col-sm-3">

			<label>Direct Members</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['direct_members']); ?></p>

		</div>

	</div>

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Group Members</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['group_members']); ?></p>

		</div>

	</div>

	

	<div class="row mb-1">

		<div class="col-sm-3">

			<label>Total Sale</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['total_sale']); ?></p>

		</div>

	</div>

	<div class="row mb-1">

		<div class="col-sm-3">

			<label>Direct Sale</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['total_direct_sale']); ?></p>

		</div>

	</div>

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Group Sale</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['total_group_sale']); ?></p>

		</div>

	</div>

	

	<div class="row mb-1">

		<div class="col-sm-3">

			<label>Direct Sale for Rewards Completion</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['direct_sale']); ?></p>

		</div>

	</div>

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Group Sale for Rewards Completion</label>

		</div>

		<div class="col-sm-9">

			<p class="text"><?php echo $validation->db_field_validate($registerRow['group_sale']); ?></p>

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Incentive</label>

		</div>

		<div class="col-sm-9">

			<p class="text">&#8377;<?php echo $validation->price_format($registerRow['incentive']); ?></p>

		</div>

	</div>

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Salary</label>

		</div>

		<div class="col-sm-9">

			<p class="text">&#8377;<?php echo $validation->price_format($registerRow['salary']); ?></p>

		</div>

	</div>

	

	<?php if($registerRow['challenges_end_date'] != "") { ?>

		<div class="row mb-1">

			<div class="col-sm-3">

				<label>Current Reward Stage for Challenges</label>

			</div>

			<div class="col-sm-9">

				<p class="text">

					<?php

					$challenges_rewardid = $registerRow['challenges_rewardid'];

					$challengesRewardResult = $db->view('rewardid,title', 'mlm_rewards', 'rewardid', "and rewardid='$challenges_rewardid' and status='active'", 'title asc');

					$challengesRewardRow = $challengesRewardResult['result'][0];

					echo $validation->db_field_validate($challengesRewardRow['title']);

					?>

				</p>

			</div>

		</div>

		<div class="row mb-1">

			<div class="col-sm-3">

				<label>Direct Sale for Challenges</label>

			</div>

			<div class="col-sm-9">

				<p class="text"><?php echo $validation->db_field_validate($registerRow['challenges_direct_sale']); ?></p>

			</div>

		</div>

		<div class="row mb-1">

			<div class="col-sm-3">

				<label>Group Sale for Challenges</label>

			</div>

			<div class="col-sm-9">

				<p class="text"><?php echo $validation->db_field_validate($registerRow['challenges_group_sale']); ?></p>

			</div>

		</div>

		<div class="row mb-3">

			<div class="col-sm-3">

				<label>End Date for Challenges</label>

			</div>

			<div class="col-sm-9">

				<p class="text"><?php echo $validation->date_format_custom($registerRow['challenges_end_date']); ?></p>

			</div>

		</div>

	<?php } ?>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Reward Stage</label>

		</div>

		<div class="col-sm-9">

			<p class="text">

				<?php

				$rewardid = $registerRow['rewardid'];

				$rewardQueryResult = $db->view('rewardid,title', 'mlm_rewards', 'rewardid', "and rewardid='$rewardid' and status='active'", 'title asc');

				$rewardRow = $rewardQueryResult['result'][0];

				echo $validation->db_field_validate($rewardRow['title']);

				?>

			</p>

		</div>

	</div> -->

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="first_name">First Name *</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['first_name']); ?>" <?php if($registerRow['first_name'] != "") echo "readonly"; ?> required />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="last_name">Last Name</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['last_name']); ?>" <?php if($registerRow['last_name'] != "") echo "readonly"; ?> />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="username">Username</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="username" id="username" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['username']); ?>" <?php if($registerRow['username'] != "") echo "readonly"; ?> />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="relation_name">S/O, D/O, W/F, Spouse *</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="relation_name" id="relation_name" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['relation_name']); ?>" <?php if($registerRow['relation_name'] != "") echo "readonly"; ?> required />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="date_of_birth">Date of Birth</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="date_of_birth" id="date_of_birth" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['date_of_birth']); ?>" <?php if($registerRow['date_of_birth'] != "") echo "readonly"; ?> />

		</div>

	</div>


	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="date_of_birth">Gender</label>

		</div>

		<div class="col-sm-9">

			<select name="gender" id="gender" class='form-control'>
				<option value="" >--Select Gender--</option>
				<option value="male" <?php if($registerRow['gender'] == "male") { echo "selected"; }?>>Male</option>
				<option value="female"  <?php if($registerRow['gender'] == "female") { echo "selected"; }?>>Female</option>
			</select>

		</div>

	</div>
	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="email">Email *</label>

		</div>

		<div class="col-sm-9">

			<input type="email" name="email" id="email" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['email']); ?>" <?php if($registerRow['email'] != "") echo "readonly"; ?> required />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="password">Password *</label>

		</div>

		<div class="col-sm-9">

			<input type="password" name="password" id="password" class="form-control" autocomplete="new-password" />

			<input type="hidden" name="old_password" id="old_password" value="<?php echo $validation->db_field_validate($registerRow['password']); ?>" />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="confirm_password">Confirm Password *</label>

		</div>

		<div class="col-sm-9">

			<input type="password" name="confirm_password" id="confirm_password" class="form-control" autocomplete="new-password" onBlur="passwordmatch();" />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="mobile">Mobile No. *</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="mobile" id="mobile" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['mobile']); ?>" <?php if($registerRow['mobile'] != "") echo "readonly"; ?> required />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="mobile_alter">Mobile No. (Alternative)</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="mobile_alter" id="mobile_alter" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['mobile_alter']); ?>" <?php if($registerRow['mobile_alter'] != "") echo "readonly"; ?> />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="address">Address *</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="address" id="address" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['address']); ?>" <?php if($registerRow['address'] != "") echo "readonly"; ?> required />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="pincode">Pincode *</label>

		</div>

		<div class="col-sm-9">

			<input type="number" name="pincode" id="pincode" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['pincode']); ?>" <?php if($registerRow['pincode'] != "") echo "readonly"; ?> required />

		</div>

	</div>

	

	<h5 class="mb-4 mt-5 text-light bg-info p-3">Nominee Details</h5>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="nominee_name">Name *</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="nominee_name" id="nominee_name" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['nominee_name']); ?>" <?php if($registerRow['nominee_name'] != "") echo "readonly"; ?> required />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="nominee_relation">Relation *</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="nominee_relation" id="nominee_relation" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['nominee_relation']); ?>" <?php if($registerRow['nominee_relation'] != "") echo "readonly"; ?> required />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="nominee_age">Age</label>

		</div>

		<div class="col-sm-9">

			<input type="number" name="nominee_age" id="nominee_age" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['nominee_age']); ?>" <?php if($registerRow['nominee_age'] != "") echo "readonly"; ?> />

		</div>

	</div>

	

	<h5 class="mb-4 mt-5 bg-info p-3 text-light">Bank Details</h5>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="bank_name">Bank Name</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="bank_name" id="bank_name" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['bank_name']); ?>" <?php if($registerRow['bank_name'] != "") echo "readonly"; ?> />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="account_number">Account Number</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="account_number" id="account_number" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['account_number']); ?>" <?php if($registerRow['account_number'] != "") echo "readonly"; ?> />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="ifsc_code">Bank Swift/IFSC Code</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="ifsc_code" id="ifsc_code" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['ifsc_code']); ?>" <?php if($registerRow['ifsc_code'] != "") echo "readonly"; ?> />

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="account_name">Account Name</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="account_name" id="account_name" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['account_name']); ?>" <?php if($registerRow['account_name'] != "") echo "readonly"; ?> />

		</div>

	</div>


	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="bankdoc">Cancel Cheque Image(s)</label>
		</div>
		<div class="col-sm-9">
			<input type="file" name="bankdoc[]" id="bankdoc" multiple />
			<input type="hidden" name="old_bankdoc" id="old_bankdoc" value="<?php echo $validation->db_field_validate($registerRow['bankdoc']); ?>" />
			<?php if($registerRow['bankdoc'] != "") { ?>
				<div class="mt-2 links">
					<?php
					$imgName = $registerRow['bankdoc'];
					$imgName = explode(" | ", $imgName);
					foreach($imgName as $img)
					{
					?>
						<div class="image-preview">
							<a href="<?php echo IMG_MAIN_LOC; echo $validation->db_field_validate($img); ?>" target="_blank"><img src="<?php echo IMG_THUMB_LOC; echo $validation->db_field_validate($img); ?>" title="<?php echo $validation->db_field_validate($img); ?>" alt="<?php echo $validation->db_field_validate($img); ?>" class="image-preview-img" /></a><Br/>
							<a href="profile.php?regid=<?php echo $regid; ?>&bankdoc=<?php echo $img; ?>&q=bankdel" class="del_link" onClick="return del();">Delete</a>
						</div>
					<?php
					}
					?>
				</div>
			<?php } ?>
			<em class="d-block mt-1">File should be Image and size under <?php echo $validation->convertToReadableSize($configRow['image_maxsize']); ?><br>Image extension should be .jpg, .jpeg, .png, .gif<br>Hold "Ctrl" key for multi-selection</em>
		</div>
	</div>

	

	<h5 class="mb-4 mt-5 bg-info text-light p-3">KYC Details</h5>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="pan_card">Pan Card</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="pan_card" id="pan_card" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['pan_card']); ?>" <?php if($registerRow['pan_card'] != "") echo "readonly"; ?> />

		</div>

	</div>

	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="panImage">Pan Card Document Image(s)</label>
		</div>
		<div class="col-sm-9">
			<input type="file" name="panImage[]" id="panImage" multiple />
			<input type="hidden" name="old_panImage" id="old_panImage" value="<?php echo $validation->db_field_validate($registerRow['panImage']); ?>" />
			<?php if($registerRow['panImage'] != "") { ?>
				<div class="mt-2 links">
					<?php
					$imgName = $registerRow['panImage'];
					$imgName = explode(" | ", $imgName);
					foreach($imgName as $img)
					{
					?>
						<div class="image-preview">
							<a href="<?php echo IMG_MAIN_LOC; echo $validation->db_field_validate($img); ?>" target="_blank"><img src="<?php echo IMG_THUMB_LOC; echo $validation->db_field_validate($img); ?>" title="<?php echo $validation->db_field_validate($img); ?>" alt="<?php echo $validation->db_field_validate($img); ?>" class="image-preview-img" /></a><Br/>
							<a href="profile.php?regid=<?php echo $regid; ?>&panImage=<?php echo $img; ?>&q=panImage" class="del_link" onClick="return del();">Delete</a>
						
						</div>
						
					<?php
					}
					?>
				</div>
			<?php } ?>
			<em class="d-block mt-1">File should be Image and size under <?php echo $validation->convertToReadableSize($configRow['image_maxsize']); ?><br>Image extension should be .jpg, .jpeg, .png, .gif<br>Hold "Ctrl" key for multi-selection</em>
		</div>
	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="aadhar_card">Aadhaar Card *</label>

		</div>

		<div class="col-sm-9">

			<input type="text" name="aadhar_card" id="aadhar_card" class="form-control" value="<?php echo $validation->db_field_validate($registerRow['aadhar_card']); ?>" <?php if($registerRow['aadhar_card'] != "") echo "readonly"; ?> required />

		</div>

	</div>

	<div class="row mb-3">
		<div class="col-sm-3">
			<label for="kycdoc">Aadhaar Card Document Image(s)</label>
		</div>
		<div class="col-sm-9">
			<input type="file" name="kycdoc[]" id="kycdoc" multiple />
			<input type="hidden" name="old_kycdoc" id="old_kycdoc" value="<?php echo $validation->db_field_validate($registerRow['kycdoc']); ?>" />
			<?php if($registerRow['kycdoc'] != "") { ?>
				<div class="mt-2 links">
					<?php
					$imgName = $registerRow['kycdoc'];
					$imgName = explode(" | ", $imgName);
					foreach($imgName as $img)
					{
					?>
						<div class="image-preview">
							<a href="<?php echo IMG_MAIN_LOC; echo $validation->db_field_validate($img); ?>" target="_blank"><img src="<?php echo IMG_THUMB_LOC; echo $validation->db_field_validate($img); ?>" title="<?php echo $validation->db_field_validate($img); ?>" alt="<?php echo $validation->db_field_validate($img); ?>" class="image-preview-img" /></a><Br/>
							<a href="profile.php?regid=<?php echo $regid; ?>&kycdoc=<?php echo $img; ?>&q=kycdel" class="del_link" onClick="return del();">Delete</a>
							
						</div>
						
					<?php
					}
					?>
				</div>
			<?php } ?>
			<em class="d-block mt-1">File should be Image and size under <?php echo $validation->convertToReadableSize($configRow['image_maxsize']); ?><br>Image extension should be .jpg, .jpeg, .png, .gif<br>Hold "Ctrl" key for multi-selection</em>
		</div>
	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label for="imgName">Profile Picture</label>

		</div>

		<div class="col-sm-9">

			<input type="file" name="imgName" id="imgName">

			<input type="hidden" name="old_imgName" id="old_imgName" value="<?php echo $validation->db_field_validate($registerRow['imgName']); ?>" />

			<?php if($registerRow['imgName'] != "") { ?>

				<div class="mt-2 links">

					<img src="<?php echo IMG_THUMB_LOC; echo $validation->db_field_validate($registerRow['imgName']); ?>" title="<?php echo $validation->db_field_validate($registerRow['imgName']); ?>" class="img-responsive mh-51" /><br>

					<a href="profile.php?regid=<?php echo $regid; ?>&q=imgdel" onClick="return del();">Delete</a>

				</div>

			<?php } ?>

			<em class="d-block mt-1">File should be Image and size under <?php echo $validation->convertToReadableSize($configRow['image_maxsize']); ?><br>Image extension should be .jpg, .jpeg, .png, .gif</em>

		</div>

	</div>

	

	<div class="row mb-3">

		<div class="col-sm-3">

			<label>Creation Date & Time</label>

		</div>

		<div class="col-sm-9">

			<?php if($registerRow['createdate'] != "") { ?>

				<p class="text"><?php echo $validation->date_format_custom($registerRow['createdate'])." at ".$validation->time_format_custom($registerRow['createtime']); ?></p>

			<?php } ?>

		</div>

	</div>

	

	<div class="row mt-4 mb-4">

		<div class="col-sm-12">

			<button type="submit" name="submit" class="btn  btn-sm mr-2 btn_submit"><i class="fas fa-save"></i>&nbsp;&nbsp;Update</button>

		</div>

	</div>

</div>

</form>

</div>

</div>

</div>



</body>

</html>