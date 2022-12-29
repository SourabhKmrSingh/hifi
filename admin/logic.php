<?php include_once("inc_config.php");

echo "<pre>";

$legs_data = array();
$designationData = array();
$leg = 1;
$memberparent = '';
$directSale_total = 0;
$membership_id = "SKG660270";
$startDate = '';
$endDate = "";

function fetch_group_sale($parent)
{
    global $db,$userid, $user_ip, $legs_data ,$leg, $startDate, $endDate;
    $treeResult = $db->view('membership_id, imgName, username, status, sponsor_id', 'mlm_registrations', 'regid', "and sponsor_id='$parent'", 'regid asc');

    if($treeResult['num_rows'] >= 1)
    {

        foreach($treeResult['result'] as $treeRow)
        {

            $membership_id_group = $treeRow['membership_id'];
            
            // echo $parent;
            $plotInventoryResult = $db->view('inventoryid','mlm_plots_inventory','inventoryid', " and sponsor_id = '$membership_id_group' and record_check = '1' and booking_date <= '{$endDate}' and booking_date >= '{$startDate}'");
            
            if($plotInventoryResult['num_rows'] >= 1){
               
                $legs_data[$leg]['groupsale'] += $plotInventoryResult['num_rows'];
               
            }
            
            $legs_data[$leg]['depth']++;
            fetch_group_sale($treeRow['membership_id']);

        }

    }else{
        
        $leg++;
    
    }
    
    return $legs_data;
}




// Challenge Checking Logic 

function checkChallenge($parent)
{    
    
    global $db, $userid, $user_ip, $leg, $slr, $designationData, $legs_data, $membership_id, $createdate, $createtime, $startDate, $endDate;
    
    $dataResult = $db->view('*', 'mlm_registrations', 'regid', " and membership_id IN('$parent')", 'regid asc');

    if($dataResult['num_rows']>=1)
    {

        foreach($dataResult['result'] as $memberRow)
        {

            $planid = $memberRow['planid'];
            $planResult = $db->view("planid",'mlm_plans','planid', " and planid = '$planid' and status = 'active'");

            if($planResult['num_rows'] >= 1){
            
                $memberRewardid = $memberRow['rewardid'];
                $cregid = $memberRow['regid'];
                    
                $callengeResult = $db->view('*', 'mlm_challenges','challengeid', " and find_in_set({$memberRewardid}, rewardids) and planid ='$planid' and challengeid not in (select challengeid from mlm_challenges_history where regid = '$cregid')", 'order_custom');


                if($callengeResult['num_rows'] >= 1){

                    $memberIdChallenge = $memberRow['membership_id'];


                    if($memberRow['status'] == 'active'){

                        foreach($callengeResult['result'] as $challengeRow){

                            $leg = 1;
                            $legs_data = array();
                            $memberparent = '';
                            $previousParent = array();

                            $totalRequiredDirectSale = $challengeRow['direct_sale'];
                            $totalRequiredGroupSale = $challengeRow['group_sale'];
                            $startDate = $challengeRow['start_date'];
                            $endDate = $challengeRow['end_date'];
                            
                            $group_sale_data = fetch_group_sale($memberRow['membership_id']);
                        
                            $biggerLeg = 0;
                            $depth = 0;

                            for($i=1; $i <= count($group_sale_data); $i++){
                                if($group_sale_data[$i]['depth'] > $depth){
                                    $biggerLeg = $i;
                                    $depth = $group_sale_data[$i]['depth'];
                                }
                            }
    
                            $group_sale_total = 0;
                            for($i=1; $i <= count($group_sale_data); $i++){
    
                                if(isset($group_sale_data[$i]['groupsale'])){
                                    if($biggerLeg == $i){
                                        $group_sale_total += $group_sale_data[$i]['groupsale'] / 2;
                                    }else{
                                        $group_sale_total += $group_sale_data[$i]['groupsale'];
                                    }
                                }
                                
                            }
    
                            $plotInventoryResult = $db->view('inventoryid','mlm_plots_inventory','inventoryid', " and sponsor_id = '$memberIdChallenge' and record_check = '1' and booking_date <= '$endDate' and booking_date >= '$startDate'");
    
                            $directSale_total = $plotInventoryResult['num_rows'];
    
                        
                            echo $memberIdChallenge . "<br>";
    
                            echo  "Group Sale: -  "  . $group_sale_total . "<br>";
                            
                            echo "Direct Sale: -  " . $directSale_total . "<br>";

                            exit();
    
                        }
                        
                    } 
                }
            } 
        
        }
        $slr++;
        checkChallenge($memberRow['sponsor_id']); 
    }
    return;
}


checkChallenge("SKG660270"); // Sponsor ID






?>