<?php
include_once("inc_config.php");

$purchaseResult = $db->view('membership_id', 'rb_purchases', 'purchaseid', "and createdate BETWEEN DATE_SUB(NOW(), INTERVAL 85 DAY) AND NOW()", 'purchaseid desc', '1', 'membership_id');
if($purchaseResult['num_rows'] >= 1)
{
	foreach($purchaseResult['result'] as $purchaseRow)
	{
		$membership_id = $validation->db_field_validate($purchaseRow['membership_id']);
		$registerResult = $db->view("first_name,last_name,email", "mlm_registrations", "regid", "and membership_id='{$membership_id}'");
		$registerRow = $registerResult['result'][0];
		
		$email = $registerRow['email'];
		$first_name = $registerRow['first_name'];
		$last_name = $registerRow['last_name'];
		$mobile = $registerRow['mobile'];
		
		if($mobile != "")
		{
			$message = "Dear {$first_name} {$last_name}, You have not placed any order from last 85 days, please place an order within 5 days to be active otherwise your membership will be expired.". PHP_EOL ."". PHP_EOL ."Thank You". PHP_EOL ."Grocery Master.";
			$send = $api->sendSMS('ARIHAN', $mobile, $message);
			if($send)
			{
				echo "Done";
			}
			else
			{
				echo "Failed";
			}
		}
	}
}
else
{
	echo "No User Found!";
}

$purchaseResult2 = $db->view('membership_id', 'rb_purchases', 'purchaseid', "and createdate BETWEEN DATE_SUB(NOW(), INTERVAL 90 DAY) AND NOW()", 'purchaseid desc', '1', 'membership_id');
if($purchaseResult2['num_rows'] >= 1)
{
	foreach($purchaseResult2['result'] as $purchaseRow2)
	{
		$membership_id2 = $validation->db_field_validate($purchaseRow2['membership_id']);
		
		$registerupdateResult = $db->update("mlm_registrations", array('status'=>'inactive'), array('membership_id'=>$membership_id2));
		
		$registerResult2 = $db->view("first_name,last_name,email", "mlm_registrations", "regid", "and membership_id='{$membership_id2}'");
		$registerRow = $registerResult2['result'][0];
		
		$email = $registerRow['email'];
		$first_name = $registerRow['first_name'];
		$last_name = $registerRow['last_name'];
		$mobile = $registerRow['mobile'];
		
		if($mobile != "")
		{
			$message = "Dear {$first_name} {$last_name}, You have not placed any order from last 90 days so your membership is expired now. Kindly contact Administrator for any kind of help.".PHP_EOL."".PHP_EOL."Thank You".PHP_EOL."Grocery Master.";
			$send = $api->sendSMS('ARIHAN', $mobile, $message);
			if($send)
			{
				echo "Done";
			}
			else
			{
				echo "Failed";
			}
		}
	}
}
else
{
	echo "No User Found!";
}
?>