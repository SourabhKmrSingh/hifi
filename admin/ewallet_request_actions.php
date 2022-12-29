<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "ewallet_request";

$q = $validation->urlstring_validate($_GET['q']);
if($q == "del")
{
	echo $validation->delete_permission();
	
	$requestid = $validation->urlstring_validate($_GET['requestid']);
	
	$delresult = $media->multiple_filedeletion('mlm_ewallet_requests', 'requestid', $requestid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
	$delresult2 = $media->filedeletion('mlm_ewallet_requests', 'requestid', $requestid, 'fileName', FILE_LOC);

	$ewalletrequestQueryResult = $db->delete("mlm_ewallet_requests", array('requestid'=>$requestid));
	if(!$ewalletrequestQueryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: ewallet_request_view.php");
		exit();
	}

	$_SESSION['success_msg'] = "{$ewalletrequestQueryResult} Record Deleted!";
	header("Location: ewallet_request_view.php");
	exit();
}

if(isset($_POST['bulk_actions']) and $_POST['bulk_actions'] != "")
{
	$bulk_actions = $validation->urlstring_validate($_POST['bulk_actions']);
	$del_items = $_POST['del_items'];
	$requestids = array();
	if(empty($del_items))
	{
		$_SESSION['error_msg'] = "Please select atleast one row to perform action!";
		header("Location: ewallet_request_view.php");
		exit();
	}
	if(isset($del_items) and $del_items != "")
	{
		foreach($del_items as $id)
		{
			array_push($requestids, "$id");
		}
		
		$requestids = implode(',', $requestids);
		
		if($bulk_actions == "delete")
		{
			$ewalletrequestQueryResult = $db->custom("DELETE from mlm_ewallet_requests where FIND_IN_SET(`requestid`, '$requestids')");
			if(!$ewalletrequestQueryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: ewallet_request_view.php");
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Deleted!";
			header("Location: ewallet_request_view.php");
			exit();
		}
		else if($bulk_actions == "pending" || $bulk_actions == "approved" || $bulk_actions == "declined" || $bulk_actions == "fulfilled")
		{
			$ewalletrequestQueryResult = $db->custom("UPDATE mlm_ewallet_requests SET status='$bulk_actions' where FIND_IN_SET(`requestid`, '$requestids')");
			if(!$ewalletrequestQueryResult)
			{
				echo mysqli_error($connect);
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Updated!";
			header("Location: ewallet_request_view.php");
			exit();
		}
	}
}
else if(isset($_POST['excel']) and $_POST['excel'] != "")
{
	@$userid = $validation->input_validate($_GET['userid']);
	@$regid = $validation->input_validate($_GET['regid']);
	@$refno = $validation->input_validate($_GET['refno']);
	@$status = strtolower($validation->input_validate($_GET['status']));
	@$datefrom = $validation->input_validate($_GET['datefrom']);
	@$dateto = $validation->input_validate($_GET['dateto']);

	$where_query = "";
	if($userid != "")
	{
		$where_query .= " and userid = '$userid'";
	}
	if($regid != "")
	{
		$where_query .= " and regid = '$regid'";
	}
	if($refno != "")
	{
		$where_query .= " and refno = '$refno'";
	}
	if($status != "")
	{
		$where_query .= " and status = '$status'";
	}
	if($datefrom != "" and $dateto != "")
	{
		$where_query .= " and createdate between '$datefrom' and '$dateto'";
	}
	//$where_query .= "and amount != '0.00'";
	
	$slr = 1;
	$rowCount = 3;
	$exportResult = $db->view("*", "mlm_ewallet_requests", "requestid", $where_query, 'requestid desc');
	if($exportResult['num_rows'] >= 1)
	{
		$phpExcel->getActiveSheet()->SetCellValue('A1', 'S. No.');
		$phpExcel->getActiveSheet()->SetCellValue('B1', 'Transaction ID');
		$phpExcel->getActiveSheet()->SetCellValue('C1', 'User ID');
		$phpExcel->getActiveSheet()->SetCellValue('D1', 'Name');
		$phpExcel->getActiveSheet()->SetCellValue('E1', 'Sponsor ID');
		$phpExcel->getActiveSheet()->SetCellValue('F1', 'Sponsor Name');
		$phpExcel->getActiveSheet()->SetCellValue('G1', 'Designation');
		$phpExcel->getActiveSheet()->SetCellValue('H1', 'Level Income');
		$phpExcel->getActiveSheet()->SetCellValue('I1', 'Direct Incentive');
		$phpExcel->getActiveSheet()->SetCellValue('J1', 'Incentive Sales');
		$phpExcel->getActiveSheet()->SetCellValue('K1', 'Salary Income');
		$phpExcel->getActiveSheet()->SetCellValue('L1', 'Total Amount');
		$phpExcel->getActiveSheet()->SetCellValue('M1', 'TDS');
		$phpExcel->getActiveSheet()->SetCellValue('N1', 'Payable');
		$phpExcel->getActiveSheet()->SetCellValue('O1', 'Status');
		$phpExcel->getActiveSheet()->SetCellValue('P1', 'Date');
		
		foreach($exportResult['result'] as $exportRow)
		{

			$memregid = $exportRow['regid'];
			$memberResult = $db->view("*", "mlm_registrations", "regid", " and regid ='$memregid'");
			$memberRow = $memberResult['result'][0];

			$rewardid = $memberRow['rewardid'];
			$rewardResult = $db->view("*", "mlm_rewards", "rewardid",  " and rewardid = '$rewardid'");
			$rewardRow = $rewardResult['result'][0];

			$dateEntry = $exportRow['createdate'];
			$startDate = date("Y-m-1", strtotime("$dateEntry - 1 month"));
			$endDate = date("Y-n-d", strtotime("last day of $dateEntry - 1 month"));

			$levelewalletResult = $db->view('sum(total_amount) as level_income', "mlm_ewallet", "ewalletid", " and type = 'credit' and regid = '$memregid' and createdate between '$startDate' and '$endDate' and reason ='Level Earnings'");

			$levelewalletRow = $levelewalletResult['result'][0];

			$cashewalletResult = $db->view('sum(total_amount) as cash_income', "mlm_ewallet", "ewalletid", " and type = 'credit' and regid = '$memregid' and createdate between '$startDate' and '$endDate' and reason ='Cash Incentive'");

			$cashewalletRow = $cashewalletResult['result'][0];

			$incentiveewalletResult = $db->view('sum(total_amount) as incentive_income', "mlm_ewallet", "ewalletid", " and type = 'credit' and regid = '$memregid' and createdate between '$startDate' and '$endDate' and reason ='Incentive - Sale Earnings'");

			$incentiveewalletRow = $incentiveewalletResult['result'][0];

			$salaryewalletResult = $db->view('sum(total_amount) as salary_income', "mlm_ewallet", "ewalletid", " and type = 'credit' and regid = '$memregid' and createdate between '$startDate' and '$endDate' and reason ='Salary Earning'");

			$salaryewalletRow = $salaryewalletResult['result'][0];

			$tdsewalletResult = $db->view('sum(tds_amount) as tds', "mlm_ewallet", "ewalletid", " and type = 'credit' and regid = '$memregid' and createdate between '$startDate' and '$endDate'");

			$tdsewalletRow = $tdsewalletResult['result'][0];


			$phpExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $slr);
			$phpExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $validation->db_field_validate($exportRow['refno']));
			$phpExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $validation->db_field_validate($exportRow['membership_id']));
			$phpExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $validation->db_field_validate($memberRow['first_name'] . " " .$memberRow['last_name']));
			$phpExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $validation->db_field_validate($memberRow['sponsor_id']));
			$phpExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $validation->db_field_validate($memberRow['sponsor_name']));
			$phpExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $validation->db_field_validate($rewardRow['title']));
			$phpExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $validation->price_format($levelewalletRow['level_income']));
			$phpExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $validation->price_format($cashewalletRow['cash_income']));
			$phpExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $validation->price_format($incentiveewalletRow['incentive_income']));
			$phpExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $validation->price_format($salaryewalletRow['salary_income']));
			$phpExcel->getActiveSheet()->SetCellValue('L'.$rowCount, ($validation->price_format($levelewalletRow['level_income'] + $cashewalletRow['cash_income'] + $incentiveewalletRow['incentive_income'] + $salaryewalletRow['salary_income'])));
			$phpExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $validation->price_format($tdsewalletRow['tds']));
			$phpExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $validation->price_format($exportRow['amount']));
			$phpExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $validation->db_field_validate($exportRow['status']));
			$phpExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $validation->db_field_validate($exportRow['createdate']));
			
			$slr++;
			$rowCount++;
		}
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="transactions_list.xlsx"');
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
	$fields_string = str_replace("excel=Download Data&", "", $fields_string);
	$fields_string = substr($fields_string, 0, -1);
	
	header("Location: ewallet_request_view.php?$fields_string");
	exit();
}
?>