<?php

include_once("inc_config.php");

include_once("login_user_check.php");



$_SESSION['active_menu'] = "register";



if(isset($_GET['mode']))

{

	$mode = $validation->urlstring_validate($_GET['mode']);

}

else

{

	$_SESSION['error_msg'] = "There is a problem. Please Try Again!";

	header("Location: register_view.php");

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

	if(isset($_GET['regid']))

	{

		$regid = $validation->urlstring_validate($_GET['regid']);

		if($_SESSION['search_filter'] != "")

		{

			$search_filter = "?".$_SESSION['search_filter'];

		}

	}

	else

	{

		$_SESSION['error_msg'] = "There is a problem. Please Try Again!";

		header("Location: register_view.php");

		exit();

	}

}



$old_membership_id = $validation->input_validate($_POST['old_membership_id']);

$old_membership_id_value = $validation->input_validate($_POST['old_membership_id_value']);

$member_check = $validation->input_validate($_POST['member_check']);

$sponsor_id = $validation->input_validate($_POST['sponsor_id']);

$sponsor_name = $validation->input_validate($_POST['sponsor_name']);

$planid = $validation->input_validate($_POST['planid']);

if($planid=='')

{

	$planid = 0;

}

$rewardid = $validation->input_validate($_POST['rewardid']);

if($rewardid=='')

{

	$rewardid = 0;

}

$first_name = $validation->input_validate($_POST['first_name']);

$last_name = $validation->input_validate($_POST['last_name']);

$username = $validation->input_validate($_POST['username']);

$relation_name = $validation->input_validate($_POST['relation_name']);

$date_of_birth = $validation->input_validate($_POST['date_of_birth']);

$email = $validation->input_validate($_POST['email']);

$password = $validation->input_validate(sha1($_POST['password']));

$confirm_password = $validation->input_validate(sha1($_POST['confirm_password']));

$old_password = $validation->input_validate($_POST['old_password']);

$mobile = $validation->input_validate($_POST['mobile']);

$mobile_alter = $validation->input_validate($_POST['mobile_alter']);

$address = $validation->input_validate($_POST['address']);

$pincode = $validation->input_validate($_POST['pincode']);

if($pincode=='')

{

	$pincode = 0;

}

$nominee_name = $validation->input_validate($_POST['nominee_name']);

$nominee_relation = $validation->input_validate($_POST['nominee_relation']);

$nominee_age = $validation->input_validate($_POST['nominee_age']);

$bank_name = $validation->input_validate($_POST['bank_name']);

$account_number = $validation->input_validate($_POST['account_number']);

$ifsc_code = $validation->input_validate($_POST['ifsc_code']);

$account_name = $validation->input_validate($_POST['account_name']);

$pan_card = $validation->input_validate($_POST['pan_card']);

$aadhar_card = $validation->input_validate($_POST['aadhar_card']);

$remarks = $validation->input_validate($_POST['remarks']);

$status = $validation->input_validate($_POST['status']);

$old_imgName = $validation->input_validate($_POST['old_imgName']);

$old_bankdoc =  $validation->input_validate($_POST['old_bankdoc']);

$old_panImage =  $validation->input_validate($_POST['old_panImage']);

$old_kycdoc =  $validation->input_validate($_POST['old_kycdoc']);



$user_ip_array = ($_POST['user_ip']!='') ? explode(", ", $validation->input_validate($_POST['user_ip'])) : array();

array_push($user_ip_array, $user_ip);

$user_ip_array = array_unique($user_ip_array);

$user_ip = implode(", ", $user_ip_array);



if($_POST['password'] != "")

{

	if($password != $confirm_password)

	{

		$_SESSION['error_msg'] = "Password and Confirm Password should be Same!";

		header("Location: register_view.php");

		exit();

	}

}



$dupresult = $db->check_duplicates('mlm_registrations', 'regid', $regid, 'username', strtolower($username), $mode);

if($dupresult >= 1)

{

	$_SESSION['error_msg'] = "Username already exists!";

	header("Location: register_view.php");

	exit();

}

$dupresult2 = $db->check_duplicates('mlm_registrations', 'regid', $regid, 'mobile', strtolower($mobile), $mode);

if($dupresult2 >= 1)

{

	$_SESSION['error_msg'] = "Mobile No. is already in use. Please take another one!";

	header("Location: register_view.php");

	exit();

}



$imgTName = $_FILES['imgName']['name'];

if($imgTName != "")

{

	$handle = new Upload($_FILES['imgName']);

    if($handle->uploaded)

	{

		$handle->file_force_extension = true;

		$handle->file_max_size = $validation->db_field_validate($configRow['image_maxsize']);

		$handle->allowed = array('image/*');

		if($configRow['large_width'] != "0" and $configRow['large_height'] != "0")

		{

			$handle->image_resize = true;

			$handle->image_x = $validation->db_field_validate($configRow['large_width']);

			$handle->image_y = $validation->db_field_validate($configRow['large_height']);

			$handle->image_no_enlarging = ($configRow['large_ratio'] === "false") ? false : true;

			$handle->image_ratio = ($configRow['large_ratio'] === "false") ? false : true;

		}

		

		$handle->process(IMG_MAIN_LOC);

		if($handle->processed)

		{

			$imgName = $handle->file_dst_name;

		}

		else

		{

			$_SESSION['error_msg'] = $handle->error.'!';

			header("Location: register_view.php");

			exit();

		}

		

		// Thumbnail Image

		$handle->file_force_extension = true;

		$handle->file_max_size = $validation->db_field_validate($configRow['image_maxsize']);

		$handle->allowed = array('image/*');

		if($configRow['thumb_width'] != "0" and $configRow['thumb_height'] != "0")

		{

			$handle->image_resize = true;

			$handle->image_x = $validation->db_field_validate($configRow['thumb_width']);

			$handle->image_y = $validation->db_field_validate($configRow['thumb_height']);

			$handle->image_no_enlarging = ($configRow['thumb_ratio'] === "false") ? false : true;

			$handle->image_ratio = ($configRow['thumb_ratio'] === "false") ? false : true;

		}

		

		$handle->process(IMG_THUMB_LOC);

		if($handle->processed)

		{

		}

		else

		{

			$_SESSION['error_msg'] = $handle->error.'!';

			header("Location: register_view.php");

			exit();

		}

		

		$handle-> clean();

	}

	else

	{

		$_SESSION['error_msg'] = $handle->error.'!';

		header("Location: register_view.php");

		exit();

    }

	

	if($mode == "edit")

	{

		$delresult = $media->filedeletion('mlm_registrations', 'regid', $regid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);

	}

}


// KYC DOCUMENT
$kycdocTName = $_FILES['kycdoc']['name'][0];
if($kycdocTName != "")
{
	$files = array();
	foreach($_FILES['kycdoc'] as $k => $l)
	{
		foreach ($l as $i => $v)
		{
			if(!array_key_exists($i, $files))
			$files[$i] = array();
			$files[$i][$k] = $v;
		}
	}
	
	$kycdoc = array();
	
	foreach ($files as $file)
	{
		$handle = new Upload($file);
		if($handle->uploaded)
		{
			$handle->file_force_extension = true;
			$handle->file_max_size = $validation->db_field_validate($configRow['image_maxsize']);
			$handle->allowed = array('image/*');
			if($configRow['large_width'] != "0" and $configRow['large_height'] != "0")
			{
				$handle->image_resize = true;
				$handle->image_x = $validation->db_field_validate($configRow['large_width']);
				$handle->image_y = $validation->db_field_validate($configRow['large_height']);
				$handle->image_no_enlarging = ($configRow['large_ratio'] === "false") ? false : true;
				$handle->image_ratio = ($configRow['large_ratio'] === "false") ? false : true;
			}
			
			$handle->process(IMG_MAIN_LOC);
			if($handle->processed)
			{
				array_push($kycdoc, $handle->file_dst_name);
			}
			else
			{
				$_SESSION['error_msg'] = $handle->error.'!';
				header("Location: product_view.php");
				exit();
			}
			
			// Thumbnail Image
			$handle->file_force_extension = true;
			$handle->file_max_size = $validation->db_field_validate($configRow['image_maxsize']);
			$handle->allowed = array('image/*');
			if($configRow['thumb_width'] != "0" and $configRow['thumb_height'] != "0")
			{
				$handle->image_resize = true;
				$handle->image_x = $validation->db_field_validate($configRow['thumb_width']);
				$handle->image_y = $validation->db_field_validate($configRow['thumb_height']);
				$handle->image_no_enlarging = ($configRow['thumb_ratio'] === "false") ? false : true;
				$handle->image_ratio = ($configRow['thumb_ratio'] === "false") ? false : true;
			}
			
			$handle->process(IMG_THUMB_LOC);
			if($handle->processed)
			{
			}
			else
			{
				$_SESSION['error_msg'] = $handle->error.'!';
				header("Location: kyc_form.php");
				exit();
			}
			
			$handle-> clean();
		}
		else
		{
			$_SESSION['error_msg'] = $handle->error.'!';
			header("Location: kyc_form.php");
			exit();
		}
	}
}

$kycdoc = implode(" | ", $kycdoc);
if($old_kycdoc != "")
{
	if($kycdoc != "")
	{
		$kycdoc = $kycdoc.' | '.$old_kycdoc;
	}
	else
	{
		$kycdoc = $old_kycdoc;
	}
}



// PAN DOCUMENT
$panImageTName = $_FILES['panImage']['name'][0];
if($panImageTName != "")
{
	$files = array();
	foreach($_FILES['panImage'] as $k => $l)
	{
		foreach ($l as $i => $v)
		{
			if(!array_key_exists($i, $files))
			$files[$i] = array();
			$files[$i][$k] = $v;
		}
	}
	
	$panImage = array();
	
	foreach ($files as $file)
	{
		$handle = new Upload($file);
		if($handle->uploaded)
		{
			$handle->file_force_extension = true;
			$handle->file_max_size = $validation->db_field_validate($configRow['image_maxsize']);
			$handle->allowed = array('image/*');
			if($configRow['large_width'] != "0" and $configRow['large_height'] != "0")
			{
				$handle->image_resize = true;
				$handle->image_x = $validation->db_field_validate($configRow['large_width']);
				$handle->image_y = $validation->db_field_validate($configRow['large_height']);
				$handle->image_no_enlarging = ($configRow['large_ratio'] === "false") ? false : true;
				$handle->image_ratio = ($configRow['large_ratio'] === "false") ? false : true;
			}
			
			$handle->process(IMG_MAIN_LOC);
			if($handle->processed)
			{
				array_push($panImage, $handle->file_dst_name);
			}
			else
			{
				$_SESSION['error_msg'] = $handle->error.'!';
				header("Location: product_view.php");
				exit();
			}
			
			// Thumbnail Image
			$handle->file_force_extension = true;
			$handle->file_max_size = $validation->db_field_validate($configRow['image_maxsize']);
			$handle->allowed = array('image/*');
			if($configRow['thumb_width'] != "0" and $configRow['thumb_height'] != "0")
			{
				$handle->image_resize = true;
				$handle->image_x = $validation->db_field_validate($configRow['thumb_width']);
				$handle->image_y = $validation->db_field_validate($configRow['thumb_height']);
				$handle->image_no_enlarging = ($configRow['thumb_ratio'] === "false") ? false : true;
				$handle->image_ratio = ($configRow['thumb_ratio'] === "false") ? false : true;
			}
			
			$handle->process(IMG_THUMB_LOC);
			if($handle->processed)
			{
			}
			else
			{
				$_SESSION['error_msg'] = $handle->error.'!';
				header("Location: kyc_form.php");
				exit();
			}
			
			$handle-> clean();
		}
		else
		{
			$_SESSION['error_msg'] = $handle->error.'!';
			header("Location: kyc_form.php");
			exit();
		}
	}
}

$panImage = implode(" | ", $panImage);
if($old_panImage != "")
{
	if($panImage != "")
	{
		$panImage = $panImage.' | '.$old_panImage;
	}
	else
	{
		$panImage = $old_panImage;
	}
}



// BANK DOCUMENT
$bankdocTName = $_FILES['bankdoc']['name'][0];
if($bankdocTName != "")
{
	$files = array();
	foreach($_FILES['bankdoc'] as $k => $l)
	{
		foreach ($l as $i => $v)
		{
			if(!array_key_exists($i, $files))
			$files[$i] = array();
			$files[$i][$k] = $v;
		}
	}
	
	$bankdoc = array();
	
	foreach ($files as $file)
	{
		$handle = new Upload($file);
		if($handle->uploaded)
		{
			$handle->file_force_extension = true;
			$handle->file_max_size = $validation->db_field_validate($configRow['image_maxsize']);
			$handle->allowed = array('image/*');
			if($configRow['large_width'] != "0" and $configRow['large_height'] != "0")
			{
				$handle->image_resize = true;
				$handle->image_x = $validation->db_field_validate($configRow['large_width']);
				$handle->image_y = $validation->db_field_validate($configRow['large_height']);
				$handle->image_no_enlarging = ($configRow['large_ratio'] === "false") ? false : true;
				$handle->image_ratio = ($configRow['large_ratio'] === "false") ? false : true;
			}
			
			$handle->process(IMG_MAIN_LOC);
			if($handle->processed)
			{
				array_push($bankdoc, $handle->file_dst_name);
			}
			else
			{
				$_SESSION['error_msg'] = $handle->error.'!';
				header("Location: product_view.php");
				exit();
			}
			
			// Thumbnail Image
			$handle->file_force_extension = true;
			$handle->file_max_size = $validation->db_field_validate($configRow['image_maxsize']);
			$handle->allowed = array('image/*');
			if($configRow['thumb_width'] != "0" and $configRow['thumb_height'] != "0")
			{
				$handle->image_resize = true;
				$handle->image_x = $validation->db_field_validate($configRow['thumb_width']);
				$handle->image_y = $validation->db_field_validate($configRow['thumb_height']);
				$handle->image_no_enlarging = ($configRow['thumb_ratio'] === "false") ? false : true;
				$handle->image_ratio = ($configRow['thumb_ratio'] === "false") ? false : true;
			}
			
			$handle->process(IMG_THUMB_LOC);
			if($handle->processed)
			{
			}
			else
			{
				$_SESSION['error_msg'] = $handle->error.'!';
				header("Location: profile.php");
				exit();
			}
			
			$handle-> clean();
		}
		else
		{
			$_SESSION['error_msg'] = $handle->error.'!';
			header("Location: profile.php");
			exit();
		}
	}
}

$bankdoc = implode(" | ", $bankdoc);
if($old_bankdoc != "")
{
	if($bankdoc != "")
	{
		$bankdoc = $bankdoc.' | '.$old_bankdoc;
	}
	else
	{
		$bankdoc = $old_bankdoc;
	}
}




if($_POST['password'] == "")

{

	$password = $old_password;

}

if($imgName == "")

{

	$imgName = $old_imgName;

}




if($old_membership_id == "")

{

	$membership_id = "";
	$current_year = date('Y');
	$current_month = date('m');
	$refResult = $db->view("MAX(membership_id_value) as membership_id_value", "mlm_registrations", "regid", "");
	$refRow = $refResult['result'][0];
	$membership_id_value = $refRow['membership_id_value'];
	$membership_id_value = $membership_id_value+1;

	$membership_id = sprintf("%03d", $membership_id_value);
	$membership_id = "HIFI". $membership_id;

	if($password == ""){
		$text_pass = "HIFI" . substr($mobile, 0, 4);
		$password = sha1($text_pass);
	}


}

else

{

	$membership_id = $old_membership_id;

	$membership_id_value = $old_membership_id_value;

}



if($_POST['password'] == "" && $password == "")

{

	$password = $old_password;

}

if($imgName == "")

{

	$imgName = $old_imgName;

}




if($planid == ""){
	$planid = 1;
}

if($rewardid == ""){
	$rewardid = 1;
}



$fields = array('membership_id'=>$membership_id, 'membership_id_value'=>$membership_id_value, 'sponsor_id'=>$sponsor_id, 'sponsor_name'=>$sponsor_name, 'planid'=>$planid, 'rewardid'=>$rewardid, 'first_name'=>$first_name, 'last_name'=>$last_name, 'username'=>$username, 'relation_name'=>$relation_name, 'date_of_birth'=>$date_of_birth, 'email'=>$email, 'password'=>$password, 'mobile'=>$mobile, 'mobile_alter'=>$mobile_alter, 'address'=>$address, 'pincode'=>$pincode, 'nominee_name'=>$nominee_name, "kycdoc"=> $kycdoc, 'panImage'=> $panImage, 'bankdoc'=> $bankdoc,'nominee_relation'=>$nominee_relation, 'nominee_age'=>$nominee_age, 'bank_name'=>$bank_name, 'account_number'=>$account_number, 'ifsc_code'=>$ifsc_code, 'account_name'=>$account_name, 'pan_card'=>$pan_card, 'aadhar_card'=>$aadhar_card, 'imgName'=>$imgName, 'remarks'=>$remarks, 'status'=>$status, 'user_ip'=>$user_ip);



if($rewardid != '0'){
	$rewardResult = $db->view("*", "mlm_rewards", "rewardid", " and rewardid = '{$rewardid}' and status='active'");
	
	if($rewardResult['num_rows'] >= 1){
		$rewardRow = $rewardResult['result'][0];
		$fields['salary'] = $rewardRow['salary'];
		$fields['incentive'] = $rewardRow['incentive'];
	}
}

if($mode == "insert")

{

	$fields['userid'] = $userid;

	$fields['createtime'] = $createtime;

	$fields['createdate'] = $createdate;

	

	$registerQueryResult = $db->insert("mlm_registrations", $fields);





	if(!$registerQueryResult)

	{

		echo mysqli_error($connect);

		exit();

	}

		

	$userPassword = $_POST['password'];
	

	// $message = "Welcome To SK Realtech Family Your User ID- {$membership_id} Pass. {$userPassword} Please Visit www.skrealtech.co.in";

	// $api->sendSMS($mobile,$message);



	$_SESSION['success_msg'] = "Record Added!";

}

else if($mode == "edit")

{

	if($member_check == "0" and $status == "active")

	{

		$fields['member_check'] = "1";

	}

	$fields['userid_updt'] = $userid;

	$fields['modifytime'] = $createtime;

	$fields['modifydate'] = $createdate;

	

	$registerQueryResult = $db->update("mlm_registrations", $fields, array('regid'=>$regid));

	if(!$registerQueryResult)

	{

		echo mysqli_error($connect);

		exit();

	}

	

	$_SESSION['success_msg'] = "Record Updated!";

}



if($member_check == "0" and $status == "active")

{

	

}



header("Location: register_view.php$search_filter");

exit();

?>