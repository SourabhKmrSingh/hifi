<?php
include_once("inc_config.php");




// For update Incentives

$memberResult3 = $db->view("incentive", "mlm_registrations", "regid", "and regid='{$memberRow['regid']}'");

$memberRow3 = $memberResult3['result'][0];

if($memberRow3['incentive'] > 0)

{

    $refno = substr(md5(rand(1, 99999)),0,6);

    $amount = $units * $memberRow3['incentive'];

    $reason = "Incentive - Sale Earnings";

    $description = "Incentive for puchasing of {$units} units Plot by {$membership_id}";

    $status = "active";

    

    $fields = array('userid'=>$userid, 'regid'=>$memberRow['regid'], 'membership_id'=>$memberRow['membership_id'], 'username'=>$memberRow['username'], 'refno'=>$refno, 'amount'=>$amount, 'type'=>'credit', 'company_type'=>'debit', 'reason'=>$reason, 'description'=>$description, 'status'=>$status, 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);

    $ewalletResult = $db->insert("mlm_ewallet", $fields);

    if(!$ewalletResult)

    {

        echo "E-Wallet is not added for Incentive! Consult Administrator";

        exit();

    }

}








?>