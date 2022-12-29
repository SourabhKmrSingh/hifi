<?php 
include_once("inc_config.php");

$inventoryid = 1;

function incentiveDistribution($parent)
{    
    global $db, $userid, $user_ip, $leg, $inventoryid, $slr, $designationData, $legs_data, $membership_id, $createdate, $createtime;
    
    $dataResult = $db->view('*', 'mlm_registrations', 'regid', " and membership_id IN('$parent')", 'regid asc');

    if($dataResult['num_rows']>=1)
    {
        foreach($dataResult['result'] as $memberRow)
        {

            $curMemRewardid = $memberRow['rewardid'];

            // Reward Details 

            $curRewardResult = $db->view("*", "mlm_rewards", "rewardid", " and rewardid = '$curMemRewardid' and status = 'active'");

            if($curRewardResult['num_rows'] >= "1"){
                $curRewardRow = $curRewardResult['result'][0];

                $curinventiveAmount = $curRewardRow['incentive'];

                $curinventoryResult = $db->view("plotid", "mlm_plots_inventory", "inventoryid", " and inventoryid = '$inventoryid'");

                $inventoryPlotid = $curinventoryResult['result'][0]['plotid'];

                $curplotResult = $db->view("units", "mlm_plots", "plotid", " and plotid = '{$inventoryPlotid}'");

                $curplotRow = $curplotResult['result'][0];

               
                if($curinventiveAmount != "0" && $curinventiveAmount != "" && $memberRow['status'] == 'active' && $curplotRow['units'] != "0"){

                    $units = $curplotRow['units'];

                    $refno = substr(md5(rand(1, 99999)),0,6);

                    $Insamount = $units * $curinventiveAmount;

                    $reason = "Incentive - Sale Earnings";

                    $description = "Incentive for puchasing of {$units} units Plot by {$membership_id}";

                    $status = "active";

                    $incentiveFields = array('userid'=>$userid, 'regid'=>$memberRow['regid'], 'membership_id'=>$memberRow['membership_id'], 'username'=>$memberRow['username'], 'refno'=>$refno, 'amount'=>$Insamount, 'type'=>'credit', 'company_type'=>'debit', 'reason'=>$reason, 'description'=>$description, 'status'=>$status, 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);

                    // echo "<pre>";

                    // print_r($incentiveFields);

                    $ewalletResult = $db->insert("mlm_ewallet", $incentiveFields);

                    if(!$ewalletResult)

                    {

                        echo "E-Wallet is not added for Incentive! Consult Administrator";

                        exit();

                    }

                    $registerwalletResult = $db->custom("update mlm_registrations set wallet_money = wallet_money+{$Insamount}, wallet_total = wallet_total+{$Insamount} where regid='{$memberRow['regid']}'");
						
                    if(!$registerwalletResult)
                
                    {
                
                        echo "Wallet is not added! Consult Administrator";
                
                    }

                }

            }

        }
        $slr++;
        incentiveDistribution($memberRow['sponsor_id']); 
    }
    return;
}


incentiveDistribution("BIP123222");  // Sponsor ID








?>