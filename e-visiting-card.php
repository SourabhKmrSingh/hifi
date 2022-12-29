<?php
include_once("inc_config.php");
include_once("login_user_check.php");
include_once("admin/classes/mpdf/mpdf.php");


$table = "";

//<br />Type: ".$validation->db_field_validate($productPurchaseRow['tax_type'])."

$memberResult = $db->view('*','mlm_registrations','regid'," and regid ='{$regid }'");
$memberRow = $memberResult['result'][0];

if($memberResult['num_rows'] == 0){
	$_SESSION['error_msg'] = "Error! Please try again later.";
	header("Location: home.php");
	exit();
}

if($memberRow['imgName'] == ""){
	$_SESSION['error_msg'] = "Please Upload A Profile Pic!";
	header("Location: home.php");
	exit();
}

$rewardid = $memberRow['rewardid'];

$rewardResult = $db->view('*','mlm_rewards','rewardid'," and rewardid='{$rewardid}'");
$rewardRow = $rewardResult['result'][0];


$html = '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>E-Visiting Card - '.$memberRow['membership_id'].'</title>
<style>
body
{
	font-family: arial;
	font-size: 10px;
}
.hind_text
{
	font-family: freeserif;
	font-weight:bold;
}

table
{
	width:100%;
}


.p-5
{
	padding:5px;
	border-bottom:1px solid #ccc !important;
	font-size:29px;
}
.logo{
	margin-top: .5rem;
}
.profile-pic{
	margin-top: 1.5rem;
}

p{
	margin:0px;
	padding: 0px;
	margin-bottom: .2rem;
}

.profile-name{
	font-size: 13px;
	font-family: arial;
	font-weight: bold;
	margin-top: 1rem;
	margin-bottom: 1px;
	letter-spacing: 0.06rem;
}
.website-url{
	margin-top: .3rem;
}
hr{
	width: 100%;
}
</style>
</head>
<body>

<div align="center" style="border: 1px solid black;">
	<img src="'. IMG_MAIN_LOC . $configRow['logo']  .'" width="100" class="logo"/>
	<div class="profile-pic" align="center">
		<img src="'. IMG_MAIN_LOC . $memberRow['imgName'] .'" width="120" height="110"/>
	</div>
	<div align="center">
		<div class="profile-name" align="center">
		'. $validation->db_field_validate($memberRow['first_name']) . " " .   $validation->db_field_validate($memberRow['last_name']) .'
		<br/>'.$validation->db_field_validate($memberRow['membership_id']).'<br>
		'. $validation->db_field_validate($rewardRow['title']).'
		</div>
		
	</div>
	<div class="website-url" align="center">
		www.skrealtech.co.in
	</div>
	<hr/>
	<div class="" align="center">
		<p class="" align="center"><b>'. $configRow['icard_company_name'] .'</b></p>
		<p class="" align="center">'. $configRow['icard_address'] .'</p>
		<p class="" align="center"><b>Email Id</b>: '. $configRow['icard_email'] .'</p>
		<p class="" align="center"><b>Mob</b> : '. $configRow['icard_mobile'] .'</p>
	</div>
</div>
</body>
</html>
';

$mpdf = new mPDF('utf-8', array(69.85,107.95), 0, '', 1, 1, 3, 3, 9, 9);

$mpdf->default_lineheight_correction = 0.4;
// LOAD a stylesheet
$stylesheet = file_get_contents('classes/mpdf/bootstrap_pdf.css');
$mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->SetColumns(1,'J');
$mpdf->SetTitle('E-Visiting Card');
$mpdf->WriteHTML($html);

$mpdf->Output('E-Visiting Card.pdf', 'I');
exit;
?>