<?php 
include_once("inc_config.php");


echo "<pre>";

//Level Income 
$levelslr = 1;
$total_amount = 40000;
$membership_id = "SKG237048";


function getAllDownlines($parent)
{
	
	global $db, $levelslr , $membership_id, $plotid, $validation, $plotinventoryid, $total_amount, $user_ip, $createdate, $createtime, $userid, $configRow;
	$dataResult = $db->view('*', 'mlm_registrations', 'regid', " and membership_id IN('$parent')", 'regid asc');
	if($dataResult['num_rows']>=1)
	{
		foreach($dataResult['result'] as $memberRow)
		{

            if($memberRow['status'] == 'active'){

                $levelResult = $db->view("levelid, title, percentage, order_custom", "mlm_levels", 'levelid', " and status= 'active' and levelid = '{$levelslr}'", "", "1");


                if($levelResult['num_rows'] >= 1){
                    $levelRow = $levelResult['result'][0];

                    // print_r($levelRow);

                    // Level Information
                    $lvl_id = $levelRow['levelid'];
                    $lvl_title = $levelRow['title'];
                    $percentage = $levelRow['percentage'];
                    $order_custom = $levelRow['order_custom'];
                    
                    // Initial Percentage - Distribution

                    if($percentage != "0.00" && $total_amount != "0.00" && $percentage != "" && $total_amount != ""){   
                        $refno = substr(md5(rand(1, 99999)),0,12);
                        
                        $reason = "Level Earnings";

                        $intialPercentage = $percentage / 3;

                        $discounted_amount = $validation->calculate_discounted_price($intialPercentage, $total_amount);
                    
                        $description = "Level {$levelslr} Earnings for puchasing of &#8377;{$validation->price_format($total_amount)} by {$membership_id}";
                    
                        $status = "active";
                        
                        $fields = array('userid'=>$userid, 'regid'=>$memberRow['regid'], 'membership_id'=>$memberRow['membership_id'], 'username'=>$memberRow['username'], 'refno'=>$refno, 'amount'=>$discounted_amount, 'type'=>'credit', 'company_type'=>'debit', 'reason'=>$reason, 'description'=>$description, 'status'=>$status, 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);

                        // print_r($fields);
                    
                        $ewalletResult = $db->insert("mlm_ewallet", $fields);
                    
                        if(!$ewalletResult)
                    
                        {
                    
                            echo "E-Wallet is not added for level percentage! Consult Administrator";
                    
                            exit();
                    
                        }
                    
                        
                    
                        $registerwalletResult = $db->custom("update mlm_registrations set wallet_money = wallet_money+{$discounted_amount}, wallet_total = wallet_total+{$discounted_amount} where regid='{$memberRow['regid']}'");
                    
                        if(!$registerwalletResult)
                    
                        {
                    
                            echo "Wallet is not added! Consult Administrator";
                    
                            exit();
                    
                        }

                    }

                    if($intialPercentage != "0.00" && $total_amount != "0.00" && $intialPercentage != "" && $total_amount != ""){ 

                        $refnoIns = substr(md5(rand(1, 99999)),0,12);
                        
                        $reasonIns = "Level Earnings";

                        $discounted_amount = $validation->calculate_discounted_price($intialPercentage, $total_amount);
                    
                        $descriptionIns = "Level {$levelslr} Earnings for puchasing of &#8377;{$validation->price_format($total_amount)} by {$membership_id}";
                    
                        $status = "active";

                        $distributionFields = array('refno'=> $refnoIns, 'regid'=> $memberRow['regid'], 'membership_id'=> $memberRow['membership_id'], 'username' => $memberRow['username'], 'plotid' => $plotid, 'plotinventoryid' => $plotinventoryid, 'plotownerid' => $membership_id, 'levelid'=> $lvl_id, 'amount'=> $total_amount,'reason' => $reasonIns, 'discription' => $descriptionIns, 'status'=> "unpaid", 'createdate'=> $createdate, 'createtime'=> $createtime);
                       
                        $closing_date = $configRow['closing_date'];

                        for($i = 1; 2 >= $i; $i++){
                            
                            $distributionFields['distribution_date'] = date("Y-m-$closing_date", strtotime("$createdate + $i months"));

                            print_r($distributionFields);

                            $db->insert('mlm_distribution_level', $distributionFields);

                        }

                    }

                }

            }

            $levelslr++; 

		}
		
		getAllDownlines($memberRow['sponsor_id']); 
	}
	return;
}

getAllDownlines('SKG660270'); // Sponsor ID will Go HERE

?>