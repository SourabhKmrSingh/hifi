<?php
include_once("inc_config.php");

if($_SESSION['mlm_regid'] != '')
{
	$_SESSION['success_msg'] = "You're Logged In!";
	header("Location: {$base_url}home{$suffix}");
	exit();
}


if(isset($_POST['token']) && $_POST['token'] === $_SESSION['csrf_token'])
{
	$sponsor_id = $validation->input_validate($_POST['sponsor_id']);
	$sponsor_name = $validation->input_validate($_POST['sponsor_name']);
	$first_name = $validation->input_validate($_POST['first_name']);
	$last_name = $validation->input_validate($_POST['last_name']);
	$username = $validation->input_validate($_POST['username']);
	$relation_name = $validation->input_validate($_POST['relation_name']);
	$date_of_birth = $validation->input_validate($_POST['date_of_birth']);
	$gender = $validation->input_validate($_POST['gender']);
	$email = $validation->input_validate($_POST['email']);
	// $password = $validation->input_validate(sha1($_POST['password']));
	// $confirm_password = $validation->input_validate(sha1($_POST['confirm_password']));
	$mobile = $validation->input_validate($_POST['mobile']);
	$address = $validation->input_validate($_POST['address']);
	$pincode = $validation->input_validate($_POST['pincode']);
	if($pincode=='')
	{
		$pincode = 0;
	}
	$nominee_name = $validation->input_validate($_POST['nominee_name']);
	$nominee_relation = $validation->input_validate($_POST['nominee_relation']);
	$nominee_age = $validation->input_validate($_POST['nominee_age']);
	if($nominee_age=='')
	{
		$nominee_age = 0;
	}
	$bank_name = $validation->input_validate($_POST['bank_name']);
	$account_number = $validation->input_validate($_POST['account_number']);
	$ifsc_code = $validation->input_validate($_POST['ifsc_code']);
	$account_name = $validation->input_validate($_POST['account_name']);
	$pan_card = $validation->input_validate($_POST['pan_card']);
	$aadhar_card = $validation->input_validate($_POST['aadhar_card']);
	$status = "inactive";
	
	$registerplanResult = $db->view('regid,planid', 'mlm_registrations', 'regid', "and membership_id='$sponsor_id' and status='active'", 'regid desc');
	if($registerplanResult['num_rows'] >= 1)
	{
		$registerplanRow = $registerplanResult['result'][0];
		$planid = $registerplanRow['planid'];
	}
	
	if($planid=='')
	{
		$planid = 0;
	}
	
	if($first_name == "" || $email == ""  || $mobile == "" || $pincode == "")
	{
		$_SESSION['error_msg'] = "Please fill all required fields!";
		header("Location: {$base_url}register{$suffix}");
		exit();
	}

	// $userlimitResult = $db->check_duplicates('mlm_registrations', 'regid', $regid, 'sponsor_id', strtolower($sponsor_id), "insert");
	// if($userlimitResult >= 3)
	// {
		// $_SESSION['error_msg'] = "You can only add only 3 members in your downline. Please motivate your team members so that you'll get their benefits";
		// header("Location: {$base_url}register{$suffix}");
		// exit();
	// }
	// $dupresult = $db->check_duplicates('mlm_registrations', 'regid', $regid, 'email', strtolower($email), "insert");
	// if($dupresult >= 1)
	// {
	// 	$_SESSION['error_msg'] = "Email-ID is already in use. Please take another one!";
	// 	header("Location: {$base_url}register{$suffix}");
	// 	exit();
	// }
	// $dupresult2 = $db->check_duplicates('mlm_registrations', 'regid', $regid, 'account_number', strtolower($account_number), "insert");
	// if($dupresult2 >= 2)
	// {
		// $_SESSION['error_msg'] = "Bank Account Number is already in use. Please take another one!";
		// header("Location: {$base_url}register{$suffix}");
		// exit();
	// }
	// $dupresult3 = $db->view('regid', 'mlm_registrations', 'regid', "and mobile='$mobile' and planid='$planid'");
	// if($dupresult3['num_rows'] >= 1)
	// {
	// 	$_SESSION['error_msg'] = "Mobile No. is already in use. Please take another one!";
	// 	header("Location: {$base_url}register{$suffix}");
	// 	exit();
	// }
	// if($pan_card != "")
	// {
	// 	$dupresult4 = $db->view('regid', 'mlm_registrations', 'regid', "and pan_card='$pan_card'");
	// 	if($dupresult4['num_rows'] >= 2)
	// 	{
	// 		$_SESSION['error_msg'] = "PAN Card is already in use. Please take another one!";
	// 		header("Location: {$base_url}register{$suffix}");
	// 		exit();
	// 	}
	// }
	// if($aadhar_card != "")
	// {
	// 	$dupresult5 = $db->view('regid', 'mlm_registrations', 'regid', "and aadhar_card='$aadhar_card'");
	// 	if($dupresult5['num_rows'] >= 2)
	// 	{
	// 		$_SESSION['error_msg'] = "Aadhar Card is already in use. Please take another one!";
	// 		header("Location: {$base_url}register{$suffix}");
	// 		exit();
	// 	}
	// }
	
	$membership_id = "";
	$current_year = date('Y');
	$current_month = date('m');
	$refResult = $db->view("MAX(membership_id_value) as membership_id_value", "mlm_registrations", "regid", "");
	$refRow = $refResult['result'][0];
	$membership_id_value = $refRow['membership_id_value'];
	$membership_id_value = $membership_id_value+1;
	// $membership_id = sprintf("%03d", $membership_id_value);
	//$membership_id = "BT".$current_year."".$current_month."".$membership_id;

	$membership_id = "SKG". substr($membership_id_value .rand(10000, 9999999),0,6);

	$text_pass = "SKG" . substr($mobile, 0, 4);
	$password = sha1($text_pass);
	
	$fields = array('membership_id'=>$membership_id, 'membership_id_value'=>$membership_id_value, 'sponsor_id'=>$sponsor_id, 'sponsor_name'=>$sponsor_name, 'planid'=>$planid, 'first_name'=>$first_name, 'last_name'=>$last_name, 'username'=>$username, 'relation_name'=>$relation_name, 'date_of_birth'=>$date_of_birth, 'gender'=> $gender,'email'=>$email, 'password'=>$password, 'mobile'=>$mobile, 'mobile_alter'=>$mobile_alter, 'address'=>$address, 'pincode'=>$pincode, 'nominee_name'=>$nominee_name, 'nominee_relation'=>$nominee_relation, 'nominee_age'=>$nominee_age, 'bank_name'=>$bank_name, 'account_number'=>$account_number, 'ifsc_code'=>$ifsc_code, 'account_name'=>$account_name, 'pan_card'=>$pan_card, 'aadhar_card'=>$aadhar_card, 'status'=>$status, 'user_ip'=>$user_ip);
	$fields['createtime'] = $createtime;
	$fields['createdate'] = $createdate;

	
	$registerResult = $db->insert("mlm_registrations", $fields);
	if(!$registerResult)
	{
		echo mysqli_error($connect);
		exit();
	}

	// if($email != "")
	// {
		// $subject = "Complete your registration with Bajrangi Traders";
		// $message = "Dear $first_name,<br><br>
					// Your Account has almost been created. You are just one step away to log in to your panel. Please click on the given link to confirm your email and activate your account.<br>
					// <a href='{$base_url}login_complete{$suffix}?email=$email&mode=jUhYg7Hu2hY12HuKiUhY2bhYhY6h&q=$regid_custom&mode2=kIjYhY786gThUjvFdXsAe57G' style='color: #1AB1D1;'>Click here to confirm your Email</a><br><br>
					// Link not working for you? Copy the url below into your browser.<br>
					// <a href='{$base_url}login_complete{$suffix}?email=$email&mode=jUhYg7Hu2hY12HuKiUhY2bhYhY6h&q=$regid_custom&mode2=kIjYhY786gThUjvFdXsAe57G' style='color: #1AB1D1;'>{$base_url}forgot-password-complete{$suffix}?email=$email&mode=jUhYg7Hu2hY12HuKiUhY2bhYhY6h&q=$regid_custom&mode2=kIjYhY786gThUjvFdXsAe57G</a><br><br>
					// Thanks and Regards<br>Bajrangi Traders
					// <br><br>This is an automated email, please do not reply.";
		
		// $mail->sendmail(array($email), $subject, $message);
	// }
	


	$_SESSION['register_msg'] = "Welcome to SKREALTECH, your account has been created successfully. Your membership ID is <b>{$membership_id}</b> and password is <b>{$text_pass}</b>. Thank You!";
	header("Location: {$base_url}");
	exit();
}
else
{
	$_SESSION['error_msg'] = "Error Occurred! Please try again.";
	header("Location: {$base_url}register{$suffix}");
	exit();
}
?>