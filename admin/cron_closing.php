<?php
include_once("inc_config.php");

$memberResult = $db->view("*",'mlm_registrations','regid', " and status ='active' and wallet_money != '0.00'");

$Today = date("d", strtotime($createdate)); 
if($memberResult['num_rows'] >= 1 && $Today == $configRow['closing_date']){
    
    foreach($memberResult['result'] as $memberRow){

        $startDate = date("Y-m-1", strtotime("{$createdate} - 1 month"));

        $endDate = date("Y-n-d", strtotime("last day of previous month"));

        $regid = $memberRow['regid'];
     
        $checkEwallet = $db->view('sum(amount) as total_amount', "mlm_ewallet", "ewalletid", " and type = 'credit' and regid = '$regid' and createdate between '$startDate' and '$endDate'");


        if($memberRow['wallet_money'] >= $configRow['min_wallet_amount']){

            $refno = substr(md5(rand(1, 99999)),0,12);
            $status = 'pending';

            $amount_req = $checkEwallet['result'][0]['total_amount'];

            if($amount_req > 0.00){
                $fields = array('regid'=>$memberRow['regid'], 'membership_id'=>$memberRow['membership_id'], 'refno'=>$refno, 'mobile'=>$memberRow['mobile'], 'bank_name'=>$memberRow['bank_name'], 'account_number'=>$memberRow['account_number'], 'ifsc_code'=>$memberRow['ifsc_code'], 'account_name'=>$memberRow['account_name'], 'amount'=>$amount_req, 'balance'=>"0.00", 'status'=>$status);
            
                $fields['createtime'] = $createtime;
                $fields['createdate'] = $createdate;
    
                $ewalletrequestResult = $db->insert("mlm_ewallet_requests", $fields);
                if(!$ewalletrequestResult)
                {
                    echo mysqli_error($connect);
                }
    
                $registerwalletResult = $db->custom("update mlm_registrations set wallet_money = wallet_money - $amount_req where regid='{$memberRow['regid']}'");
                if(!$registerwalletResult)
                {
                    echo "Member Wallet is not added! Consult Administrator";
                    exit();
                }
    
            }

           
        }

    }

}

?>