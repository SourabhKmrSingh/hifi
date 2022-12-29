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

	header("Location: plot_inventory_history_view.php$search_filter");

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

	if(isset($_GET['historyid']))

	{

		$historyid = $validation->urlstring_validate($_GET['historyid']);

	}

	else

	{

		$_SESSION['error_msg'] = "There is a problem. Please Try Again!";

		header("Location: plot_inventory_history_view.php$search_filter");

		exit();

	}

}



if($_SESSION['search_filter'] != "")

{

	$search_filter = "?".$_SESSION['search_filter'];

}



$record_check = $validation->input_validate($_POST['record_check']);

if($record_check=='')

{

	$record_check = 0;

}

$inventoryid = $validation->input_validate($_POST['inventoryid']);

if($inventoryid=='')

{

	$inventoryid = 0;

}

$total_amount = $validation->input_validate($_POST['total_amount']);

if($total_amount == "")

{

	$total_amount = 0;

}

$balance_amount = $validation->input_validate($_POST['balance_amount']);

if($balance_amount == "")

{

	$balance_amount = 0;

}

$plot_size = $validation->input_validate($_POST['plot_size']);

if($plot_size == "")

{

	$plot_size = 0;

}

$units = $validation->input_validate($_POST['units']);

if($units == "")

{

	$units = 0;

}

$regid = $validation->input_validate($_POST['regid']);

if($regid=='')

{

	$regid = 0;

}

$name = $validation->input_validate($_POST['name']);

$membership_id = $validation->input_validate($_POST['membership_id']);

$sponsor_id = $validation->input_validate($_POST['sponsor_id']);

$sponsor_name = $validation->input_validate($_POST['sponsor_name']);

$bank_name = $validation->input_validate($_POST['bank_name']);

$account_number = $validation->input_validate($_POST['account_number']);

$ifsc_code = $validation->input_validate($_POST['ifsc_code']);

$account_name = $validation->input_validate($_POST['account_name']);

$planid = $validation->input_validate($_POST['planid']);

$refno = $validation->input_validate($_POST['refno']);

$refno_value = $validation->input_validate($_POST['refno']); 

if($planid=='')

{

	$planid = 0;

}

$username = $validation->input_validate($_POST['username']);

$payment_type = $validation->input_validate($_POST['payment_type']);

$payment_mode = $validation->input_validate($_POST['payment_mode']);

$payment_process = $validation->input_validate($_POST['payment_process']);

$amount = $validation->input_validate($_POST['amount']);

if($amount=='')

{

	$amount = 0;

}

$cheque_details = $validation->input_validate($_POST['cheque_details']);

$description = mysqli_real_escape_string($connect, $_POST['description']);

$status = $validation->input_validate($_POST['status']);

$old_imgName = $validation->input_validate($_POST['old_imgName']);

$old_fileName = $validation->input_validate($_POST['old_fileName']);



if($amount > $balance_amount)

{

	$_SESSION['error_msg'] = "Maximum Transaction should be under ₹{$validation->price_format($balance_amount)}";

	header("Location: plot_inventory_history_view.php$search_filter");

	exit();

}



$registerResult = $db->view('mobile', 'mlm_registrations', 'regid', "and regid = '$regid'");

$registerRow = $registerResult['result'][0];

$mobile = $validation->db_field_validate($registerRow['mobile']);



$inventorytotalResult = $db->view("SUM(amount) as paid_amount", "mlm_plots_inventory_history", "historyid", "and status = 'active' and inventoryid='{$inventoryid}' and regid='{$regid}'");

$inventorytotalRow = $inventorytotalResult['result'][0];

$paid_amount = $inventorytotalRow['paid_amount'];



$balance_amount = $balance_amount - $amount;



$user_ip_array = ($_POST['user_ip']!='') ? explode(", ", $validation->input_validate($_POST['user_ip'])) : array();

array_push($user_ip_array, $user_ip);

$user_ip_array = array_unique($user_ip_array);

$user_ip = implode(", ", $user_ip_array);



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

			header("Location: plot_inventory_history_view.php$search_filter");

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

			header("Location: plot_inventory_history_view.php$search_filter");

			exit();

		}

		

		$handle-> clean();

	}

	else

	{

		$_SESSION['error_msg'] = $handle->error.'!';

		header("Location: plot_inventory_history_view.php$search_filter");

		exit();

    }

	

	if($mode == "edit")

	{

		$delresult = $media->filedeletion('mlm_plots_inventory_history', 'historyid', $historyid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);

	}

}



$fileTName = $_FILES['fileName']['name'];

if($fileTName != "")

{	

	$handle = new Upload($_FILES['fileName']);

    if($handle->uploaded)

	{

		$handle->file_force_extension = true;

		$handle->file_max_size = $validation->db_field_validate($configRow['file_maxsize']);

		$handle->allowed = array('application/*', 'text/csv', 'application/zip');

		

		$handle->process(FILE_LOC);

		if($handle->processed)

		{

			$fileName = $handle->file_dst_name;

		}

		else

		{

			$_SESSION['error_msg'] = $handle->error.'!';

			header("Location: plot_inventory_history_view.php$search_filter");

			exit();

		}

		

		$handle-> clean();

	}

	else

	{

		$_SESSION['error_msg'] = $handle->error.'!';

		header("Location: plot_inventory_history_view.php$search_filter");

		exit();

    }

	

	if($mode == "edit")

	{

		$delresult = $media->filedeletion('mlm_plots_inventory_history', 'historyid', $historyid, 'fileName', FILE_LOC);

	}

}



if($imgName == "")

{

	$imgName = $old_imgName;

}

if($fileName == "")

{

	$fileName = $old_fileName;

}



if($mode == "insert"){

	$refno = "";

	$current_year = date('Y');

	$current_month = date('m');

	$refResult = $db->view("MAX(refno_value) as refno_value", "mlm_plots_inventory_history", "historyid", "");

	$refRow = $refResult['result'][0];

	$refno_value = $refRow['refno_value'];

	$refno_value = $refno_value+1;

	$refno = $refno_value;

	//$refno = sprintf("%03d", $refno_value);

	//$refno = "BT".$current_year."".$current_month."".$refno;

	$refno = $refno;

} else if($mode == "edit"){

	if($refno == ""){

		$refno = "";

		$current_year = date('Y');

		$current_month = date('m');

		$refResult = $db->view("MAX(refno_value) as refno_value", "mlm_plots_inventory_history", "historyid", "");

		$refRow = $refResult['result'][0];

		$refno_value = $refRow['refno_value'];

		$refno_value = $refno_value+1;

		$refno = $refno_value;

		//$refno = sprintf("%03d", $refno_value);

		//$refno = "BT".$current_year."".$current_month."".$refno;

		$refno = $refno;

	}

}


if($total_amount == "" || $total_amount == 0.00){
	$_SESSION['error_msg'] = "Error! Please Try again.";
	header("Location: plot_inventory_view.php");
	exit();
}



$fields = array('inventoryid'=>$inventoryid, 'regid'=>$regid, 'refno'=>$refno, 'refno_value'=>$refno_value, 'name'=>$name, 'membership_id'=>$membership_id, 'sponsor_id'=>$sponsor_id, 'sponsor_name'=>$sponsor_name, 'bank_name'=>$bank_name, 'account_number'=>$account_number, 'ifsc_code'=>$ifsc_code, 'account_name'=>$account_name, 'payment_type'=>$payment_type, 'payment_mode'=>$payment_mode, 'payment_process'=>$payment_process, 'total_amount'=>$total_amount, 'amount'=>$amount, 'balance_amount'=>$balance_amount, 'cheque_details'=>$cheque_details, 'description'=>$description, 'imgName'=>$imgName, 'fileName'=>$fileName, 'status'=>$status, 'user_ip'=>$user_ip);



$fields2 = array('total_amount'=>$total_amount, 'amount'=>$amount, 'balance_amount'=>$balance_amount, 'user_ip'=>$user_ip);

$fields2['userid_updt'] = $userid;

$fields2['modifytime'] = $createtime;

$fields2['modifydate'] = $createdate;



if($record_check == "0" and $status == "active" and $payment_process == "Closed")

{

	$fields2['record_check'] = "1";
	$fields2['booking_date'] = $createdate;

}

if($mode == "insert")

{

	$fields['userid'] = $userid;

	$fields['createtime'] = $createtime;

	$fields['createdate'] = $createdate;

	if($payment_process == "Closed"){

		$fields2['payment_process'] = "Closed";

		$fields2['booking_date'] = $createdate;
	
	}

	$inventoryhistoryResult = $db->insert("mlm_plots_inventory_history", $fields);

	if(!$inventoryhistoryResult)

	{

		echo mysqli_error($connect);

		exit();

	}

	

	$inventoryResult = $db->update("mlm_plots_inventory", $fields2, array('inventoryid'=>$inventoryid));

	if(!$inventoryResult)

	{

		echo mysqli_error($connect);

		exit();

	}

	

	$refno = substr(md5(rand(1, 99999)),0,6);

	$paid_amount = $amount;

	$reason = "Deduction on Payment";

	$description = "Deduction on puchasing of &#8377;{$validation->price_format($total_amount)} by {$membership_id}";

	$status = "active";

	

	$fields = array('userid'=>$userid, 'regid'=>$regid, 'membership_id'=>$membership_id, 'username'=>$username, 'refno'=>$refno, 'amount'=>$paid_amount, 'type'=>'debit', 'company_type'=>'credit', 'reason'=>$reason, 'description'=>$description, 'status'=>$status, 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);

	$ewalletResult = $db->insert("mlm_ewallet", $fields);

	if(!$ewalletResult)

	{

		echo "E-Wallet is not added on Payment! Consult Administrator";

		exit();

	}

	

	$registerwalletResult = $db->custom("update mlm_registrations set total_debit = total_debit+{$paid_amount} where regid='{$regid}'");

	if(!$registerwalletResult)

	{

		echo "Wallet is not added! Consult Administrator";

		exit();

	}

	

	$recipient_no = $mobile;
	

	$smsinventoryResult = $db->view("plotid", "mlm_plots_inventory", 'inventoryid', " and inventoryid = '$inventoryid'");

	$smsplotid = $smsinventoryResult['result'][0]['plotid'];

	$smsPlotResult = $db->view("title", "mlm_plots", 'plotid', " and plotid = '$smsplotid'");

	$smsPlottitle = $smsPlotResult['result'][0]['title'];

	
	$message = "Sir we have received as EMI of Sohna Grand City Plot No. {$smsPlottitle} ₹ {$amount} Thanks Please visit www.skrealtech.co.in";

	$send = $api->sendSMS($recipient_no, $message);
	$send = $api->sendSMS("9811072765", $message);
	
	if(!$send)

	{

		echo "Failed";

	}

	

	$_SESSION['success_msg'] = "Record Added!";

}

else if($mode == "edit")

{

	$fields['userid_updt'] = $userid;

	$fields['modifytime'] = $createtime;

	$fields['modifydate'] = $createdate;

	

	$inventoryhistoryResult = $db->update("mlm_plots_inventory_history", $fields, array('historyid'=>$historyid));

	if(!$inventoryhistoryResult)

	{

		echo mysqli_error($connect);

		exit();

	}

	

	$inventoryResult = $db->update("mlm_plots_inventory", $fields2, array('inventoryid'=>$inventoryid));

	if(!$inventoryResult)

	{

		echo mysqli_error($connect);

		exit();

	}

	

	$registerwalletResult = $db->custom("update mlm_registrations set total_debit = total_debit+{$paid_amount} where regid='{$regid}'");

	if(!$registerwalletResult)

	{

		echo "Wallet is not added! Consult Administrator";

		exit();

	}

	

	$_SESSION['success_msg'] = "Record Updated!";

}



// if($record_check == "0" and $status == "active" and $payment_process == "Closed")

// {

// 	// Current Member Details 


// 	$curMemberResult = $db->view("*","mlm_registrations",'regid'," and membership_id = '$membership_id' ");


// 	if($curMemberResult['num_rows'] >= 1){

// 		$curMemberRow = $curMemberResult['result'][0];

// 	}else{

// 		$_SESSION['error_msg'] = "Error! Please Try again.";
// 		header("Location: plot_inventory_history_view.php$search_filter");
// 		exit();

// 	}

// 	//Level Income 
// 	$levelslr = 1;
// 	$plotinventoryid = $inventoryid;

// 	function getAllDownlines($parent)
// 	{
		
// 		global $db, $levelslr , $membership_id, $validation, $plotinventoryid, $total_amount, $user_ip, $createdate, $createtime, $userid, $configRow;
// 		$dataResult = $db->view('*', 'mlm_registrations', 'regid', " and membership_id IN('$parent')", 'regid asc');
// 		if($dataResult['num_rows']>=1)
// 		{
// 			foreach($dataResult['result'] as $memberRow)
// 			{

// 				if($memberRow['status'] == 'active'){

// 					$levelResult = $db->view("levelid, title, percentage, intialPercentage, instParcentage,numInst, order_custom", "mlm_levels", 'levelid', " and status= 'active' and levelid = '{$levelslr}'", "", "1");


// 					if($levelResult['num_rows'] >= 1){
// 						$levelRow = $levelResult['result'][0];

// 						// Level Information
// 						$lvl_id = $levelRow['levelid'];
// 						$intialPercentage  = $levelRow['intialPercentage'];
// 						$instParcentage = $levelRow['instParcentage'];
// 						$numInst = $levelRow['numInst'];
						
// 						// Initial Percentage - Distribution

// 						if($intialPercentage != "0.00" && $total_amount != "0.00" && $intialPercentage != "" && $total_amount != ""){   

// 							$refno = substr(md5(rand(1, 99999)),0,12);
// 							$reason = "Level Earnings";

// 							$discounted_amount = $validation->calculate_discounted_price($intialPercentage, $total_amount);
						
// 							$description = "Level {$levelslr} Earnings for puchasing of &#8377;{$validation->price_format($total_amount)} by {$membership_id}";
						
// 							$status = "active";
							
// 							$fields = array('userid'=>$userid, 'regid'=>$memberRow['regid'], 'membership_id'=>$memberRow['membership_id'], 'username'=>$memberRow['username'], 'refno'=>$refno, 'amount'=>$discounted_amount, 'type'=>'credit', 'company_type'=>'debit', 'reason'=>$reason, 'description'=>$description, 'status'=>$status, 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);

						
// 							$ewalletResult = $db->insert("mlm_ewallet", $fields);
						
// 							if(!$ewalletResult)
						
// 							{
						
// 							    echo "E-Wallet is not added for level percentage! Consult Administrator";
						
// 							}
						
							
						
// 							$registerwalletResult = $db->custom("update mlm_registrations set wallet_money = wallet_money+{$discounted_amount}, wallet_total = wallet_total+{$discounted_amount} where regid='{$memberRow['regid']}'");
						
// 							if(!$registerwalletResult)
						
// 							{
						
// 							    echo "Wallet is not added! Consult Administrator";
						
// 							}

// 						}

// 						// Distribution Table Entry

// 						if($instParcentage != "0.00" && $total_amount != "0.00" && $instParcentage != "" && $total_amount != ""){ 

// 							$refnoIns = substr(md5(rand(1, 99999)),0,12);
							
// 							$reasonIns = "Level Earnings";

// 							$discounted_amount = $validation->calculate_discounted_price($intialPercentage, $total_amount);
						
// 							$descriptionIns = "Level {$levelslr} Earnings for puchasing of &#8377;{$validation->price_format($total_amount)} by {$membership_id}";
						
// 							$status = "active";

// 							$distributionFields = array('refno'=> $refnoIns, 'regid'=> $memberRow['regid'], 'membership_id'=> $memberRow['membership_id'], 'username' => $memberRow['username'], 'plotinventoryid' => $plotinventoryid, 'plotownerid' => $membership_id, 'levelid'=> $lvl_id, 'amount'=> $total_amount,'reason' => $reasonIns, 'discription' => $descriptionIns, 'status'=> "unpaid", 'createdate'=> $createdate, 'createtime'=> $createtime);
						
// 							$closing_date = $configRow['closing_date'];


// 							$curDay = date("d", strtotime($createdate));

// 							if($curDay < $closing_date){

// 								for($i = 0; $numInst > $i; $i++){
								
// 									$distributionFields['distribution_date'] = date("Y-m-$closing_date", strtotime("$createdate + $i months"));
	
// 									$db->insert('mlm_distribution_level', $distributionFields);
	
// 								}

// 							}else{

// 								for($i = 1; $numInst >= $i; $i++){
								
// 									$distributionFields['distribution_date'] = date("Y-m-$closing_date", strtotime("$createdate + $i months"));
	
// 									$db->insert('mlm_distribution_level', $distributionFields);
	
// 								}

// 							}

// 						}

// 					}

// 				}

// 				$levelslr++; 

// 			}
			
// 			getAllDownlines($memberRow['sponsor_id']); 
// 		}
// 		return;
// 	}

// 	getAllDownlines($curMemberRow['sponsor_id']); 


// 	// Reward Check Details

// 	$legs_data = array();
// 	$designationData = array();
// 	$leg = 1;
// 	$memberparent = '';
// 	$previousParent = array();
// 	$directSale_total = 0;

// 	function group_sale_leg_wise($parent)
// 	{
// 		global $db,$userid, $user_ip, $legs_data ,$leg, $previousParent, $createdate, $createtime;
// 		$treeResult = $db->view('membership_id,imgName,username,status, sponsor_id', 'mlm_registrations', 'regid', "and sponsor_id='$parent'", 'regid asc');

// 		if($treeResult['num_rows'] >= 1)
// 		{

// 			foreach($treeResult['result'] as $treeRow)
// 			{
// 				$membership_id_group = $treeRow['membership_id'];
				
// 				$plotInventoryResult = $db->view('inventoryid','mlm_plots_inventory','inventoryid', " and sponsor_id = '$membership_id_group' and record_check = '1'");

// 				if($plotInventoryResult['num_rows'] >= 1){
				
// 					$legs_data[$leg]['groupsale'] += $plotInventoryResult['num_rows'];
				
// 				}
				
// 				$legs_data[$leg]['depth']++;
// 				group_sale_leg_wise($treeRow['membership_id']);

// 			}

// 		}else{

// 			$leg++;
		
// 		}
		
// 		return $legs_data;
// 	}



// 	function fetch_designations($parent, $required_rewardid)
// 	{
// 		global $db, $leg, $designationData;
// 		$treeResult = $db->view('membership_id,imgName,username,status', 'mlm_registrations', 'regid', "and sponsor_id='$parent'", 'regid asc');

// 		if($treeResult['num_rows'] >= 1)
// 		{
// 			foreach($treeResult['result'] as $treeRow)
// 			{
// 				$membership_id_des = $treeRow['membership_id'];

// 				$rewardResult = $db->view('rewardid','mlm_registrations','regid'," and rewardid = '$required_rewardid' and membership_id = '$membership_id_des' and status = 'active'");

// 				if($rewardResult['num_rows'] >= 1){
// 					foreach($rewardResult['result'] as $rewardRow){
// 						$designationData[$leg]['total_members']++;
// 						$designationData[$leg]['membership_id'] .= $membership_id_des . ",";
// 					}
// 				}
			
// 				fetch_designations($treeRow['membership_id'], $required_rewardid);
// 			}
// 		}
// 		else{
// 			$leg++;
// 		}
// 		return $designationData;
// 	}


// 	// Reward Checking Logic 

// 	function promotionCheck($parent)
// 	{    
// 		global $db, $userid, $user_ip, $leg, $slr, $designationData, $legs_data, $membership_id, $createdate, $createtime;
		
// 		$dataResult = $db->view('*', 'mlm_registrations', 'regid', " and membership_id IN('$parent')", 'regid asc');

// 		if($dataResult['num_rows']>=1)
// 		{
// 			foreach($dataResult['result'] as $memberRow)
// 			{

// 				$planid = $memberRow['planid'];
// 				$planResult = $db->view("planid",'mlm_plans','planid', " and planid = '$planid' and status = 'active'");

// 				if($planResult['num_rows'] >= 1){
				
// 				$memberRewardid = $memberRow['rewardid'];
					
// 				$promotionalResult = $db->view('*', 'mlm_rewards','rewardid', " and rewardid > '{$memberRewardid}' and planid ='$planid'", 'order_custom','1');


// 				if($promotionalResult['num_rows'] >= 1){
// 					$promotionalRow = $promotionalResult['result'][0];
					
// 					$rewardid = $promotionalRow['rewardid'];
// 					$title = $promotionalRow['title'];
// 					$direct_sale = $promotionalRow['direct_sale'];
// 					$group_sale = $promotionalRow['group_sale'];
// 					$incentive = $promotionalRow['incentive'];
// 					$condition_rewardid = $promotionalRow['condition_rewardid'];
// 					$condition_members = $promotionalRow['condition_members'];
// 					$condition_legs = $promotionalRow['condition_legs'];
// 					$salary = $promotionalRow['salary'];

// 					$membership_id_Reward = $memberRow['membership_id'];

// 					if($memberRow['status'] == 'active'){
// 						$leg = 1;
// 						$legs_data = array();
// 						$memberparent = '';
// 						$previousParent = array();

// 						$group_sale_data = group_sale_leg_wise($memberRow['membership_id']);
                   
// 						$biggerLeg = 0;
// 						$depth = 0;
	
// 						for($i=1; $i <= count($group_sale_data); $i++){
// 							if($group_sale_data[$i]['depth'] > $depth){
// 								$biggerLeg = $i;
// 								$depth = $group_sale_data[$i]['depth'];
// 							}
// 						}
	
// 						$group_sale_total = 0;
// 						for($i=1; $i <= count($group_sale_data); $i++){
	
// 							if(isset($group_sale_data[$i]['groupsale'])){
// 								if($biggerLeg == $i){
// 									$group_sale_total += $group_sale_data[$i]['groupsale'] / 2;
// 								}else{
// 									$group_sale_total += $group_sale_data[$i]['groupsale'];
// 								}
// 							}
							
// 						}
	


// 						$plotInventoryResult = $db->view('inventoryid','mlm_plots_inventory','inventoryid', " and sponsor_id = '$membership_id_Reward' and record_check = '1'");

// 						$directSale_total = $plotInventoryResult['num_rows'];

// 						$leg = 1;
// 						$designationData = array();

// 						if($condition_rewardid != "0"){
// 							$data1 = fetch_designations($memberRow['membership_id'], $condition_rewardid);
// 							$total_members_data = 0;
// 							foreach($data1 as $memberCount){
// 								$total_members_data += $memberCount['total_members'];
// 							}
// 						}

// 						if($group_sale_total >= $group_sale && $directSale_total >= $direct_sale){

// 							$memberRegidReward = $memberRow['regid'];

// 							$description = "{$title} Completion on Purchase by {$membership_id}";

// 							if($condition_rewardid != 0){
						
// 								if($total_members_data >= $condition_members && count($data1) >= $condition_legs){

// 									$rewardHistoryFields = array('userid'=>$userid, 'regid'=>$memberRegidReward, 'rewardid'=>$rewardid, 'membership_id'=> $membership_id_Reward, 'username'=> $memberRow['username'],'direct_sale'=> $direct_sale, 'group_sale'=>$group_sale, 'description'=>$description,'status' =>"fullfilled", 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);

// 									$rewardHistoryInsert = $db->insert('mlm_rewards_history',$rewardHistoryFields);

// 									if(!$rewardHistoryInsert){
// 										echo "Error! Reward History is not updated";
// 									}

// 									$updateUser = $db->custom("UPDATE mlm_registrations SET rewardid = '$rewardid', incentive='$incentive', salary = '$salary' WHERE regid='{$memberRegidReward}'");

// 								}

// 							}else if($condition_rewardid == 0){

// 								$rewardHistoryFields = array('userid'=>$userid, 'regid'=>$memberRegidReward, 'rewardid'=>$rewardid, 'membership_id'=> $membership_id_Reward, 'username'=> $memberRow['username'],'direct_sale'=> $direct_sale, 'group_sale'=>$group_sale, 'description'=>$description,'status' =>"fullfilled", 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);

// 								$rewardHistoryInsert = $db->insert('mlm_rewards_history',$rewardHistoryFields);

// 								if(!$rewardHistoryInsert){
// 									echo "Error! Reward History is not updated";
// 								}

// 								$updateUser = $db->custom("UPDATE mlm_registrations SET rewardid = '$rewardid', incentive='$incentive', salary = '$salary' WHERE regid='{$memberRegidReward}'");

// 							}
// 						}
// 					} 
// 				}

// 				} 
			
// 			}
// 			$slr++;
// 			promotionCheck($memberRow['sponsor_id']); 
// 		}
// 		return;
// 	}


// 	promotionCheck($curMemberRow['sponsor_id']); // Sponsor ID



// 	// Incentive Distribution Logic
	
// 	function incentiveDistribution($parent)
// 	{    

// 		global $db, $userid, $user_ip, $leg, $plotinventoryid, $slr, $designationData, $legs_data, $membership_id, $createdate, $createtime;
		
// 		$dataResult = $db->view('*', 'mlm_registrations', 'regid', " and membership_id IN('$parent')", 'regid asc');

// 		if($dataResult['num_rows']>=1)
// 		{
// 			foreach($dataResult['result'] as $memberRow)
// 			{

// 				$curMemRewardid = $memberRow['rewardid'];

// 				// Reward Details 

// 				$curRewardResult = $db->view("*", "mlm_rewards", "rewardid", " and rewardid = '$curMemRewardid' and status = 'active'");

// 				if($curRewardResult['num_rows'] >= "1"){

// 					$curRewardRow = $curRewardResult['result'][0];

// 					$curinventiveAmount = $curRewardRow['incentive'];

// 					$curinventoryResult = $db->view("plotid", "mlm_plots_inventory", "inventoryid", " and inventoryid = '$plotinventoryid'");

// 					$inventoryPlotid = $curinventoryResult['result'][0]['plotid'];

// 					$curplotResult = $db->view("units", "mlm_plots", "plotid", " and plotid = '{$inventoryPlotid}'");

// 					$curplotRow = $curplotResult['result'][0];

				
// 					if($curinventiveAmount != "0" && $curinventiveAmount != "" && $memberRow['status'] == 'active' && $curplotRow['units'] != "0"){

// 						$units = $curplotRow['units'];

// 						$refno = substr(md5(rand(1, 99999)),0,6);

// 						$Insamount = $units * $curinventiveAmount;

// 						$reason = "Incentive - Sale Earnings";

// 						$description = "Incentive for puchasing of {$units} units Plot by {$membership_id}";

// 						$status = "active";

// 						$incentiveFields = array('userid'=>$userid, 'regid'=>$memberRow['regid'], 'membership_id'=>$memberRow['membership_id'], 'username'=>$memberRow['username'], 'refno'=>$refno, 'amount'=>$Insamount, 'type'=>'credit', 'company_type'=>'debit', 'reason'=>$reason, 'description'=>$description, 'status'=>$status, 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);


// 						$ewalletResult = $db->insert("mlm_ewallet", $incentiveFields);

// 						if(!$ewalletResult)

// 						{

// 							echo "E-Wallet is not added for Incentive! Consult Administrator";

// 							exit();

// 						}

// 						$registerwalletResult = $db->custom("update mlm_registrations set wallet_money = wallet_money+{$Insamount}, wallet_total = wallet_total+{$Insamount} where regid='{$memberRow['regid']}'");
							
// 						if(!$registerwalletResult)
					
// 						{
					
// 							echo "Wallet is not added! Consult Administrator";
					
// 						}

// 					}

// 				}

// 			}

// 			$slr++;
// 			incentiveDistribution($memberRow['sponsor_id']); 

// 		}

// 		return;

// 	}

// 	incentiveDistribution($curMemberRow['sponsor_id']);  // Sponsor ID

// }



header("Location: plot_inventory_history_view.php$search_filter");

exit();

?>