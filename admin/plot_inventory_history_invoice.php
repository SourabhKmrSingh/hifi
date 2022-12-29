<?php
include_once("inc_config.php");
include_once("login_user_check.php");
include_once("classes/mpdf/mpdf.php");


//<br />Type: ".$validation->db_field_validate($productPurchaseRow['tax_type'])."

if(!isset($_GET['historyid']) && $_GET['historyid'] == ""){
	$_SESSION['error_msg'] = "Error! Please try again later.";
	header("Location: home.php");
	exit();
}

$historyid = $validation->db_field_validate($_GET['historyid']);


$plotInventoryHistoryResult = $db->view('*','mlm_plots_inventory_history','historyid'," and historyid='{$historyid}'");
$plotInventoryHistoryRow = $plotInventoryHistoryResult['result'][0];

$regid = $plotInventoryHistoryRow['regid']; 
$memberResult = $db->view('*','mlm_registrations','regid'," and regid='{$regid}'");
$memberRow = $memberResult['result'][0];

$inventoryid = $plotInventoryHistoryRow['inventoryid'];
$plotInventoryResult = $db->view('*','mlm_plots_inventory','inventoryid'," and inventoryid='{$inventoryid}'");
$plotInventoryRow = $plotInventoryResult['result'][0];

$projectid = $plotInventoryRow['projectid']; 
$projectResult = $db->view('*','mlm_projects','projectid'," and projectid='{$projectid}'");
$projectRow = $projectResult['result'][0];


$plotid = $plotInventoryRow['plotid'];
$plotResult = $db->view('*','mlm_plots','plotid'," and plotid='{$plotid}'");
$plotRow = $plotResult['result'][0];

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Receipt - '.$memberRow['membership_id'].'</title>
<style>
body
{
	font-family: freeserif;
	font-size:15px;
	background-position: 80px 100px; 
	transform: rotate(45deg);
}
.hind_text
{
	font-family: freeserif;
	font-weight:bold;
}
.footer{
	margin-top: 2rem;
}
table
{
	width:100%;
}

ol li
{
	font-size:16px;
	margin-bottom: 0.3rem;
}
.p-5
{
	padding:5px;
	border-bottom:1px solid #ccc !important;
	font-size:29px;
}

.profile-pic{
	margin-top: 2rem;
}


.profile-name{
	font-size: 1rem;
	font-family: arial;
	font-weight: bold;
	margin-top: 1rem;
	margin-bottom: 3px;
	letter-spacing: 0.06rem;
}
.website-url{
	margin-top: .8rem;
}
.header{
	border: 1px solid black;
	padding: 0.3rem 1rem;
}
.header h3{
	letter-spacing: 7px;
}
.align-center{
	vertical-align: center;
}
hr{
	width: 100%;
	height: .1rem;
}

.header tr td{
	vertical-align: center;
}
.header-info{
	text-align:center;
}

td p{
	font-size: 15px;
}

.footer p,
.footer ol li{
	font-size: 16px;
}


</style>
</head>
<body>

<div align="center">
   	<table class="header">
	   <tr >
			<td class="align-center" style="vertical-align: middle;width: 100px;"><img src="'. IMG_MAIN_LOC . $configRow['logo'] .'" width="80"/></td>
			<td align="left">
				<div class="header-info">
					<h3 align="center">'. $configRow['icard_company_name'] .'</h3>
					<p align="center">'. $configRow['icard_address'] .'</p>
					<p align="center">Mobile No: '. $configRow['icard_mobile'] .' &nbsp;&nbsp;&nbsp; Email Id: '. $configRow['icard_email'] .'</p>
				</div>
			</td>
	   </tr>
	</table>
	<h4 align="center" style="margin-top: 20px;"><b>RECEIPT<b/>
	<hr/>
	</h4>
	<table>
		<tr>
			<td>
				<p><b>Name :</b> '. $validation->db_field_validate($memberRow['first_name']) . " " . $validation->db_field_validate($memberRow['last_name']) .'</p>
				<p><b>Father/Husband/Son/Daughter : </b> '. $validation->db_field_validate($memberRow['relation_name']) .'</p>
				<p><b>Project/Phase : </b> '. $validation->db_field_validate($projectRow['title']) .'</p>
				<p><b>Plot No : </b>'. $plotRow['title'] .'</p>
				<p><b>Plot Size :</b> '. $plotRow['plot_size'] .'</p>
				<p><b>Dimension :</b> '. $plotRow['dimensions'] .'</p>
				<p><b>Rate/Sq Yard :</b>  '. $plotRow['amount_sqryard'] .' Sq Yard</p>
				<p><b>Total Plot Val :</b>  '. $plotInventoryHistoryRow['total_amount'] .'</p>
			</td>
			<td width="250">
				<p><b>Receipt No : </b> &nbsp; <b>'. $plotInventoryHistoryRow['refno'] .' </b></p>
				<p><b>Receipt Date : </b> '. date("d-M-Y",strtotime($plotInventoryHistoryRow['createdate'])) .'</p><Br>
				<p><b>Phone : </b> '. $memberRow['mobile'] .'</p>
				<p><b>Address : </b> '. $memberRow['address'] .'</p><br>
				<p><b>Payment Type : </b> '. $plotInventoryHistoryRow['payment_type'] .'</p>
			</td>
		</tr>
	</table>
	<hr/>
	<div>
		<p>We have received <b>'. $validation->price_format($plotInventoryHistoryRow['amount']) .'</b> Rs/- as '. $plotInventoryHistoryRow['payment_mode'] .' with following payment details</p>
		<table>
			<tr>
				<td>
					<p><b>Amount : </b> '. $validation->price_format($plotInventoryHistoryRow['amount']) .'</p>
					<p><b>Bank Name : </b> '. $plotInventoryHistoryRow['bank_name'] .'</p>
					<p><b>Amount in Words : </b>  '. $validation->AmountInWords($plotInventoryHistoryRow['amount']) .'</p>
				</td>
				<td width="250">
					<p><b>Mode of Payment :</b> '. $plotInventoryHistoryRow['payment_mode'] .'</p>
					<p><b>Ch/DD/TransNo :</b> '. $plotInventoryHistoryRow['cheque_details'] .'</p>
				</td>
			</tr>
		</table>
	</div>
	<hr/>
	<table>
		<tbody>
			<tr>
				<td align="left">
					<p>Cheque are subject to realization</p>
					<p><b>Note: Token strictly valid for 7 days</b></p>
				</td>
				<td align="right">
					<p><b>For '.  $configRow['icard_company_name'] .'
					</b></p><br><br>
					<p><b>Authorized Signature</b></p>
				</td>
			</tr>
		</tbody>
	</table>
    <div class="footer">
		<p><b>नियम व् शर्तें</b></p>
		<ol align="left">
			<li>प्लाट या दुकान की बुकिंग राशि कुल राशि का 40% होगी !</li>
			<li>प्लाट या दुकान की की बुकिंग राशि वापस नहीं होगी !</li>
			<li>आपातकालीन स्थिति मैं प्लाट या दुकान निरस्त केवल टोकन की तारीख से 30 दिन के अंदर ही लिया जा सकता है और दी हुई राशि का 40% राशि काटकर बकाया राशि चैक  द्वारा 60 दिन के बाद दिया जायेगा !</li>
			<li>चैक प्लाट धारक के नाम से ही दिया जायेगा !</li>
			<li>जिस तारीख को प्लाट या दुकान बुक  होती है उसी तारीख को क़िस्त  की तारीख माना जायेगा !</li>
			<li>समय पर भुगतान न करने पर Rs.50/- प्रतिदिन की पेनल्टी देनी होगी !</li>
			<li>चैक बाउंस होने की स्थिति मैं Rs.500/- की पेनल्टी देनी होगी !</li>
			<li>नकद चैक या किसी भी तरीके से किये गए भुगतान की रसीद अवश्य लें अन्यथा बिना रसीद भुगतान की जिम्मेदारी कंपनी की नहीं होगी !</li>
			<li>सभी डिसीजन कंपनी के पास सुरक्षित रहेंगे !</li>
			<li>सभी प्रकार के विवादों का अधिकार क्षेत्र दिल्ली होगा !</li>
		</ol>
    </div>
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
$mpdf->SetTitle('RECEIPT');
$mpdf->WriteHTML($html);

$mpdf->Output('receipt.pdf', 'I');
exit;
?>