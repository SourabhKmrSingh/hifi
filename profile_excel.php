<?php
include_once("inc_config.php");
include_once("login_user_check.php");

@$userid = $validation->input_validate($_GET['userid']);
@$name = strtolower($validation->input_validate($_GET['name']));
@$email = strtolower($validation->input_validate($_GET['email']));
@$mobile = strtolower($validation->input_validate($_GET['mobile']));
@$status = strtolower($validation->input_validate($_GET['status']));
@$datefrom = $validation->input_validate($_GET['datefrom']);
@$dateto = $validation->input_validate($_GET['dateto']);

$where_query = "";
$where_query .= " and regid = '$regid'";

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
	$phpExcel->getActiveSheet()->SetCellValue('F1', 'Reward Designation');
	$phpExcel->getActiveSheet()->SetCellValue('G1', 'Mobile No.');
	$phpExcel->getActiveSheet()->SetCellValue('H1', 'PAN No.');
	$phpExcel->getActiveSheet()->SetCellValue('I1', 'Aadhaar No.');
	$phpExcel->getActiveSheet()->SetCellValue('J1', 'Email');
	$phpExcel->getActiveSheet()->SetCellValue('K1', 'Pincode');
	$phpExcel->getActiveSheet()->SetCellValue('L1', 'Bank Name');
	$phpExcel->getActiveSheet()->SetCellValue('M1', 'Account Number');
	$phpExcel->getActiveSheet()->SetCellValue('N1', 'Bank Swift/IFSC Code');
	$phpExcel->getActiveSheet()->SetCellValue('O1', 'Account Name');
	$phpExcel->getActiveSheet()->SetCellValue('P1', 'Status');
	$phpExcel->getActiveSheet()->SetCellValue('Q1', 'Date');
	
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
		$phpExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $validation->db_field_validate($rewardRow['title']));
		$phpExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $validation->db_field_validate($exportRow['mobile']));
		$phpExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $validation->db_field_validate($exportRow['pan_card']));
		$phpExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $validation->db_field_validate($exportRow['aadhar_card']));
		$phpExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $validation->db_field_validate($exportRow['email']));
		$phpExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $validation->db_field_validate($exportRow['pincode']));
		$phpExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $validation->db_field_validate($exportRow['bank_name']));
		$phpExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $validation->db_field_validate($exportRow['account_number']));
		$phpExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $validation->db_field_validate($exportRow['ifsc_code']));
		$phpExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $validation->db_field_validate($exportRow['account_name']));
		$phpExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $validation->db_field_validate(ucwords($exportRow['status'])));
		$phpExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $validation->db_field_validate($exportRow['createdate']));
		
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
?>