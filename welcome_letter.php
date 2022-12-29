<?php
include_once("inc_config.php");
include_once("login_user_check.php");
include_once("admin/classes/mpdf/mpdf.php");


$table = "";

$memberResult = $db->view('*','mlm_registrations','regid'," and regid ='{$regid}'");
$memberRow = $memberResult['result'][0];

if($memberResult['num_rows'] == 0){
	$_SESSION['error_msg'] = "Error! Please try again later.";
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
<title>Welcome Letter - '.$memberRow['membership_id'].'</title>
<style>
body
{
	font-family: arial;
	font-size: 13px;
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

<div align="center">
	<img src="'. IMG_MAIN_LOC . $configRow['logo']  .'" width="100" class="logo"/>
    <br><br>
    <p>To,</p><br>
    <p><b>' .  $memberRow['first_name'] . " " . $memberRow['last_name']  . '</b></p>
    <p>'. $memberRow['address'] . " - " . $memberRow['pincode'] .'</p><br>
    <p><b>Dear Mr. / Mrs.</b></p><br>
    <p>Congratulations on becoming an Independent Associate with SK REAL TECH.</p><br>
    <p>We heartly welcome you for becoming a part of our SK GROUP Marketing Family. We 
    wish to assure you that you have taken the right decision to not only join the fastest but 
    the most unique business opportunity in the world, which can be successfully 
    accomplished at your own Time.</p><br>
    <p>Your login details are as mentioned below:</p>
    <p><b>User ID : '. $memberRow['membership_id'] .'</b></p>
    <p><b>Password : SKG' . substr($memberRow['mobile'],0,4) . '</b></p><Br>
    <p>SK REAL TECH is a company that offers Revenue Sharing Program focusing on building 
    customer relations.</p><br>
    <p>We are confident that you will receive gratification for your involvement with SK GROUP 
    and we wish you success with us.</p><br>
    <p>Let’s move towards building a successful story with SK GROUP</p><br>
    <p><b>Please Note: We are providing you an opportunity to earn money which is optional, your 
    earning will depend directly to the amount of efforts you put in directly to develop your 
    own business with us.</b></p><br>
    <p>With Regards,</p>
    <p>Sumit Kumar Sharma</p>
    <p>SK REAL TECH</p><br>
    <p>Acceptance & Declaration</p><br>
    <p>I hereby solemnly declare that the business concept has been explained to me in my vernacular 
    language by my sponsor. I further declare that I have myself read and understood the terms and 
    conditions of membership and agree with them and shall abide by these on my registration as 
    Independent Distributor.</p><br>
    <p><b>For becoming a registered Independent Associate in SK REAL TECH please print this 
    letter and upload in your KYC section.</b></p><br>
    <p><b>After verification you will become a registered an Independent Associate with SK GROUP.</b></p><br>
    <p>Welcome to our family. Let’s start your journey together.</p><br><br>

    <p style="text-align: right;"><b>Admin Off:</b> SK REAL TECH</p>
    <p style="text-align: right;">PLOT NO 4-5, 2ND Floor Rama Park</p>
    <p style="text-align: right;">Uttam Nagar New Delhi -110059.</p>
    
</div>
</body>
</html>
';

$mpdf = new mPDF('utf-8');

$mpdf->default_lineheight_correction = 0.4;
// LOAD a stylesheet
$stylesheet = file_get_contents('classes/mpdf/bootstrap_pdf.css');
$mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->SetColumns(1,'J');
$mpdf->SetTitle('Welcome Letter');
$mpdf->WriteHTML($html);

$mpdf->Output('Welcome-letter.pdf', 'I');
exit;
?>