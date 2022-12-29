<?php 
include_once("inc_config.php");

$closingDate = $configRow['closing_date'];
$closing_day = date("d", strtotime($createdate));
// $closing_day = date("d", strtotime('05-05-2022'));

$legs_data = array();
$leg = 1;
$memberparent = '';
$previousParent = array();
$directSale_total = 0;

$memberResult = $db->view("*", 'mlm_registrations','regid', " and status = 'active' and rewardid > 1");

if($memberResult['num_rows'] >= 1 && $closing_day == $closingDate){

    foreach($memberResult['result'] as $memberRow){

        $memRewardId = $memberRow['rewardid'];

        $rewardResult = $db->view('*',"mlm_rewards",'rewardid'," and status = 'active' and rewardid = '$memRewardId'");

        if($rewardResult['num_rows'] >= 1){

            $rewardRow = $rewardResult['result'][0];

            $rewardTitle = $rewardRow['title'];
            $rewardSalary = $rewardRow['salary'];

           

            $currentMonth = date("m", strtotime($createdate));
            $checkSalaryStatus = $db->view("*", "rb_salary_tracking", "salaryid", " and status='paid' and membership_id ='{$memberRow['membership_id']}' and month = '$currentMonth'");

            if($rewardSalary != 0.00 && $rewardSalary != ""  && $checkSalaryStatus['num_rows'] <= 0){
                
                $refno = substr(md5(rand(1, 99999)),0,6);

                $regid = $memberRow['regid'];

                $planResult = $db->view("*","mlm_plans", "planid", " and planid IN (select planid from mlm_registrations where regid ='$regid')"); 

                $planRow = $planResult['result'][0];

                $tds_percent = $planRow['tds'];

                $tds_amount = $rewardSalary * ($tds_percent / 100);

                $amount = $rewardSalary - $tds_amount;

                $reason = "Salary Earning";

                $description = "Salary for {$rewardTitle} Designation";

                $status = "active";

                $salaryFields = array('userid'=>$userid, 'regid'=>$memberRow['regid'], 'membership_id'=>$memberRow['membership_id'], 'username'=>$memberRow['username'], 'refno'=>$refno,'total_amount'=> $rewardSalary,'amount'=>$amount, "tds_amount"=> $tds_amount, 'tds_percent'=> $tds_percent, 'type'=>'credit', 'company_type'=>'debit', 'reason'=>$reason, 'description'=>$description, 'status'=>$status, 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);


                $ewalletResult = $db->insert("mlm_ewallet", $salaryFields);

                if(!$ewalletResult)

                {

                    echo "E-Wallet is not added for Incentive! Consult Administrator";

                    exit();

                }

                $registerwalletResult = $db->custom("update mlm_registrations set wallet_money = wallet_money+{$amount}, wallet_total = wallet_total+{$amount} where regid='{$memberRow['regid']}'");
                    
                if(!$registerwalletResult)
            
                {
            
                    echo "Wallet is not added! Consult Administrator";
            
                }

                // Update Salary Status

                $salaryTrackFields = array('refno' => $refno, 'regid'=> $memberRow['regid'], 'membership_id'=> $memberRow['membership_id'], 'username'=> $memberRow['username'], 'status'=> "paid", 'month' => $currentMonth,'createtime'=> $createtime, 'createdate'=> $createdate);

                $db->insert("rb_salary_tracking", $salaryTrackFields);
            
            }
            
        }
            
    }

}

?>