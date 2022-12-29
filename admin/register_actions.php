<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "register";

$q = $validation->urlstring_validate($_GET['q']);
if($q == "del")
{
	echo $validation->delete_permission();
	
	$regid = $validation->urlstring_validate($_GET['regid']);
	
	$delresult = $media->filedeletion('mlm_registrations', 'regid', $regid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
	
	$registerQueryResult = $db->delete("mlm_registrations", array('regid'=>$regid));
	if(!$registerQueryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: register_view.php");
		exit();
	}

	$_SESSION['success_msg'] = "{$registerQueryResult} Record Deleted!";
	header("Location: register_view.php");
	exit();
}

if(isset($_POST['bulk_actions']) and $_POST['bulk_actions'] != "")
{
	$bulk_actions = $validation->urlstring_validate($_POST['bulk_actions']);
	$del_items = $_POST['del_items'];
	$regids = array();
	if(empty($del_items))
	{
		$_SESSION['error_msg'] = "Please select atleast one row to perform action!";
		header("Location: register_view.php");
		exit();
	}
	if(isset($del_items) and $del_items != "")
	{
		foreach($del_items as $id)
		{
			if($bulk_actions == "delete")
			{
				array_push($regids, "$id");
				
				$delresult = $media->filedeletion('mlm_registrations', 'regid', $id, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
			}
			else if($bulk_actions == "active" || $bulk_actions == "inactive")
			{
				array_push($regids, "$id");
			}
		}
		
		$regids = implode(',', $regids);
		
		if($bulk_actions == "delete")
		{
			$registerQueryResult = $db->custom("DELETE from mlm_registrations where FIND_IN_SET(`regid`, '$regids')");
			if(!$registerQueryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: register_view.php");
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Deleted!";
			header("Location: register_view.php");
			exit();
		}
		else if($bulk_actions == "active" || $bulk_actions == "inactive")
		{
			$registerQueryResult = $db->custom("UPDATE mlm_registrations SET status='$bulk_actions' where FIND_IN_SET(`regid`, '$regids')");
			if(!$registerQueryResult)
			{
				echo mysqli_error($connect);
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Updated!";
			header("Location: register_view.php");
			exit();
		}
	}
}
else if(isset($_POST['excel']) and $_POST['excel'] != "")
{
	@$userid = $validation->input_validate($_GET['userid']);
	@$name = strtolower($validation->input_validate($_GET['name']));
	@$email = strtolower($validation->input_validate($_GET['email']));
	@$mobile = strtolower($validation->input_validate($_GET['mobile']));
	@$status = strtolower($validation->input_validate($_GET['status']));
	@$datefrom = $validation->input_validate($_GET['datefrom']);
	@$dateto = $validation->input_validate($_GET['dateto']);

	$where_query = "";
	if($userid != "")
	{
		$where_query .= " and userid = '$userid'";
	}
	if($name != "")
	{
		$where_query .= " and LOWER(first_name) LIKE '%$name%' OR LOWER(last_name) LIKE '%$name%'";
	}
	if($email != "")
	{
		$where_query .= " and email = '$email'";
	}
	if($mobile != "")
	{
		$where_query .= " and mobile = '$mobile'";
	}
	if($status != "")
	{
		$where_query .= " and status = '$status'";
	}
	if($datefrom != "" and $dateto != "")
	{
		$where_query .= " and createdate between '$datefrom' and '$dateto'";
	}
	
	$slr = 1;
	$rowCount = 3;
	$exportResult = $db->view("*", "mlm_registrations", "regid", $where_query, 'regid desc');
	if($exportResult['num_rows'] >= 1)
	{
		$phpExcel->getActiveSheet()->SetCellValue('A1', 'S. No.');
		$phpExcel->getActiveSheet()->SetCellValue('B1', 'Membership ID');
		$phpExcel->getActiveSheet()->SetCellValue('C1', 'Name');
		$phpExcel->getActiveSheet()->SetCellValue('D1', 'Sponsor ID');
		$phpExcel->getActiveSheet()->SetCellValue('E1', 'Sponsor Name');
		// $phpExcel->getActiveSheet()->SetCellValue('F1', 'Reward Designation');
		$phpExcel->getActiveSheet()->SetCellValue('F1', 'Mobile No.');
		$phpExcel->getActiveSheet()->SetCellValue('G1', 'PAN No.');
		$phpExcel->getActiveSheet()->SetCellValue('H1', 'Aadhaar No.');
		$phpExcel->getActiveSheet()->SetCellValue('I1', 'Email');
		$phpExcel->getActiveSheet()->SetCellValue('J1', 'Pincode');
		$phpExcel->getActiveSheet()->SetCellValue('K1', 'Bank Name');
		$phpExcel->getActiveSheet()->SetCellValue('L1', 'Account Number');
		$phpExcel->getActiveSheet()->SetCellValue('M1', 'Bank Swift/IFSC Code');
		$phpExcel->getActiveSheet()->SetCellValue('N1', 'Account Name');
		$phpExcel->getActiveSheet()->SetCellValue('O1', 'Status');
		$phpExcel->getActiveSheet()->SetCellValue('P1', 'Date');
		
		foreach($exportResult['result'] as $exportRow)
		{
			$rewardid = $exportRow['rewardid'];
			$rewardResult = $db->view('rewardid,title', 'mlm_rewards', 'rewardid', "and rewardid='$rewardid'", 'order_custom asc');
			$rewardRow = $rewardResult['result'][0];
			
			$phpExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $slr);
			$phpExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $validation->db_field_validate($exportRow['membership_id']));
			$phpExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $validation->db_field_validate($exportRow['first_name'].' '.$exportRow['last_name']));
			$phpExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $validation->db_field_validate($exportRow['sponsor_id']));
			$phpExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $validation->db_field_validate($exportRow['sponsor_name']));
			// $phpExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $validation->db_field_validate($rewardRow['title']));
			$phpExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $validation->db_field_validate($exportRow['mobile']));
			$phpExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $validation->db_field_validate($exportRow['pan_card']));
			$phpExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $validation->db_field_validate($exportRow['aadhar_card']));
			$phpExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $validation->db_field_validate($exportRow['email']));
			$phpExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $validation->db_field_validate($exportRow['pincode']));
			$phpExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $validation->db_field_validate($exportRow['bank_name']));
			$phpExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $validation->db_field_validate($exportRow['account_number']));
			$phpExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $validation->db_field_validate($exportRow['ifsc_code']));
			$phpExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $validation->db_field_validate($exportRow['account_name']));
			$phpExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $validation->db_field_validate(ucwords($exportRow['status'])));
			$phpExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $validation->db_field_validate($exportRow['createdate']));
			
			$slr++;
			$rowCount++;
		}
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="members-list.xlsx"');
		header('Cache-Control: max-age=0');
		
		$writer = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
		$writer->save('php://output');
		
		//$_SESSION['success_msg'] = "{$exportResult['num_rows']} Record(s) Downloaded Successfully!";
		//header("Location: transaction_view.php");
		exit();
	}
	else
	{
		$_SESSION['error_msg'] = "There is no record in the database!";
		header("Location: transaction_view.php");
		exit();
	}
}
else
{
	$fields = $_POST;
	
	foreach($fields as $key=>$value)
	{
		$fields_string .= $key.'='.$value.'&';
	}
	rtrim($fields_string, '&');
	$fields_string = str_replace("bulk_actions=&", "", $fields_string);
	$fields_string = substr($fields_string, 0, -1);
	
	header("Location: register_view.php?$fields_string");
	exit();
}
?>