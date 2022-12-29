<?php include_once("inc_config.php");

$distributionResult = $db->view("*", "mlm_distribution_level", 'distributionId', " and status='unpaid' and distribution_date = '$creatdate'"); // For Production 

// $distributionResult = $db->view("*", "mlm_distribution_level", 'distributionId', " and status='unpaid' and distribution_date = '2022-06-05'"); // For Testing


if($distributionResult['num_rows'] >= 1){

    foreach($distributionResult['result'] as $distributionRow){

        $refno = substr(md5(rand(1, 99999)),0,12);
        $reason = $distributionRow['reason'];

        $total_amount = $distributionRow['amount'];
        $levelid = $distributionRow['levelid'];

        $levelResult = $db->view("*", "mlm_levels", 'levelid'," and levelid = '{$levelid}' and status ='active'");

        if($levelResult['num_rows'] >= 1){

            $levelRow = $levelResult['result'][0];
            
            $regid = $distributionRow['regid'];
            $planResult = $db->view("*","mlm_plans", "planid", " and planid IN (select planid from mlm_registrations where regid ='$regid')");



            if($planResult['num_rows'] >= 1){

                $planRow = $planResult['result'][0];

                $tds_percent = $planRow['tds'];

                $discounted_amount = $distributionRow['amount'];
                
                $tds_amount = $discounted_amount * ($tds_percent / 100);

                $reason = $distributionRow['reason'];
                
                $description = $distributionRow['discription'];
            
                $status = "active";

                $currentAmount = $discounted_amount - $tds_amount;
                
                $fields = array('userid'=>$userid, 'regid'=>$distributionRow['regid'], 'membership_id'=>$distributionRow['membership_id'], 'username'=>$distributionRow['username'], 'refno'=>$refno, 'total_amount'=> $discounted_amount,'amount'=>$currentAmount, "tds_amount"=> $tds_amount, 'tds_percent'=> $tds_percent, 'type'=>'credit', 'company_type'=>'debit', 'reason'=>$reason, 'description'=>$description, 'status'=>$status, 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);
    
    
    
                $ewalletResult = $db->insert("mlm_ewallet", $fields);
            
                if(!$ewalletResult)
            
                {
            
                    echo "E-Wallet is not added for level percentage! Consult Administrator";
            
                }
            
                $registerwalletResult = $db->custom("update mlm_registrations set wallet_money = wallet_money+{$currentAmount}, wallet_total = wallet_total+{$currentAmount} where regid='{$distributionRow['regid']}'");
            
                if(!$registerwalletResult)
            
                {
            
                    echo "Wallet is not added! Consult Administrator";
            
                }
    
    
                $db->update("mlm_distribution_level", array("status"=> 'paid'), array("distributionId" => $distributionRow['distributionId']));
            
            }

          

        }

    }

}

?>