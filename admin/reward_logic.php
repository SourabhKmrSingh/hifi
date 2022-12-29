<?php include_once("inc_config.php");

echo "<pre>";

$legs_data = array();
$designationData = array();
$leg = 1;
$memberparent = '';
$previousParent = array();
$directSale_total = 0;
$membership_id = "SKG660270";

function group_sale_leg_wise($parent)
{
    global $db,$userid, $user_ip, $legs_data ,$leg, $previousParent, $createdate, $createtime;
    $treeResult = $db->view('membership_id,imgName,username,status, sponsor_id', 'mlm_registrations', 'regid', "and sponsor_id='$parent'", 'regid asc');

    if($treeResult['num_rows'] >= 1)
    {

        foreach($treeResult['result'] as $treeRow)
        {

            $membership_id_group = $treeRow['membership_id'];
            
            $plotInventoryResult = $db->view('inventoryid','mlm_plots_inventory','inventoryid', " and sponsor_id = '$membership_id_group' and record_check = '1'");

            if($plotInventoryResult['num_rows'] >= 1){
               
                $legs_data[$leg]['groupsale'] += $plotInventoryResult['num_rows'];
               
            }
            
            $legs_data[$leg]['depth']++;
            group_sale_leg_wise($treeRow['membership_id']);

        }

    }else{
        
        $leg++;
    
    }

    
    return $legs_data;
}


function fetch_designations($parent, $required_rewardid)
{
    global $db, $leg, $designationData;
    $treeResult = $db->view('membership_id,imgName,username,status', 'mlm_registrations', 'regid', "and sponsor_id='$parent'", 'regid asc');

    if($treeResult['num_rows'] >= 1)
    {
        foreach($treeResult['result'] as $treeRow)
        {
            $membership_id_des = $treeRow['membership_id'];

            $rewardResult = $db->view('rewardid','mlm_registrations','regid'," and rewardid = '$required_rewardid' and membership_id = '$membership_id_des' and status = 'active'");

            if($rewardResult['num_rows'] >= 1){
                foreach($rewardResult['result'] as $rewardRow){
                    $designationData[$leg]['total_members']++;
                    $designationData[$leg]['membership_id'] .= $membership_id_des . ",";
                }
            }
        
            fetch_designations($treeRow['membership_id'], $required_rewardid);
        }
    }
    else{
        $leg++;
    }
    return $designationData;
}


// Reward Checking Logic 

function promotionCheck($parent)
{    
    global $db, $userid, $user_ip, $leg, $slr, $designationData, $legs_data, $membership_id, $createdate, $createtime;
    
    $dataResult = $db->view('*', 'mlm_registrations', 'regid', " and membership_id IN('$parent')", 'regid asc');

    if($dataResult['num_rows']>=1)
    {
        foreach($dataResult['result'] as $memberRow)
        {

            $planid = $memberRow['planid'];
            $planResult = $db->view("planid",'mlm_plans','planid', " and planid = '$planid' and status = 'active'");

            if($planResult['num_rows'] >= 1){
            
            $memberRewardid = $memberRow['rewardid'];
                
            $promotionalResult = $db->view('*', 'mlm_rewards','rewardid', " and rewardid > '{$memberRewardid}' and planid ='$planid'", 'order_custom','1');


            if($promotionalResult['num_rows'] >= 1){
                $promotionalRow = $promotionalResult['result'][0];

                print_r($promotionalRow);
                
                $rewardid = $promotionalRow['rewardid'];
                $title = $promotionalRow['title'];
                $direct_sale = $promotionalRow['direct_sale'];
                $group_sale = $promotionalRow['group_sale'];
                $incentive = $promotionalRow['incentive'];
                $condition_rewardid = $promotionalRow['condition_rewardid'];
                $condition_members = $promotionalRow['condition_members'];
                $condition_legs = $promotionalRow['condition_legs'];
                $salary = $promotionalRow['salary'];

                $membership_id_Reward = $memberRow['membership_id'];

                if($memberRow['status'] == 'active'){
                    $leg = 1;
                    $legs_data = array();
                    $memberparent = '';
                    $previousParent = array();

                    $group_sale_data = group_sale_leg_wise($memberRow['membership_id']);
                   
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



                    $plotInventoryResult = $db->view('inventoryid','mlm_plots_inventory','inventoryid', " and sponsor_id = '$membership_id_Reward' and record_check = '1'");

                    $directSale_total = $plotInventoryResult['num_rows'];

                    $leg = 1;
                    $designationData = array();

                    if($condition_rewardid != "0"){
                        $data1 = fetch_designations($memberRow['membership_id'], $condition_rewardid);
                        $total_members_data = 0;
                        foreach($data1 as $memberCount){
                            $total_members_data += $memberCount['total_members'];
                        }
                    }

                    // Data Testing
                    echo $membership_id_Reward . "<br>";

                    echo  "Group Sale:"  . $group_sale . " -  "  . $group_sale_total . "<br>";
                    
                    echo "Direct Sale:" . $direct_sale . " -  " . $directSale_total . "<br>";

                    echo "Total Members" . $total_members_data . " - Cond." . $condition_members . "<br>";

                    echo "Total Legs" . count($data1) . " - Cond." . $condition_legs . "<br><br>";




                    if($group_sale_total >= $group_sale && $directSale_total >= $direct_sale){

                        $memberRegidReward = $memberRow['regid'];

                        $description = "{$title} Completion on Purchase by {$membership_id}";

                        if($condition_rewardid != 0 && $rewardid > 2){
                       
                            if($total_members_data >= $condition_members && count($data1) >= $condition_legs){

                                $rewardHistoryFields = array('userid'=>$userid, 'regid'=>$memberRegidReward, 'rewardid'=>$rewardid, 'membership_id'=> $membership_id_Reward, 'username'=> $memberRow['username'],'direct_sale'=> $direct_sale, 'group_sale'=>$group_sale, 'description'=>$description,'status' =>"fullfilled", 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);

                                // $rewardHistoryInsert = $db->insert('mlm_rewards_history',$rewardHistoryFields);

                                // if(!$rewardHistoryInsert){
                                //     echo "Error! Reward History is not updated";
                                // }

                                // $updateUser = $db->custom("UPDATE mlm_registrations SET rewardid = '$rewardid', incentive='$incentive', salary = '$salary' WHERE regid='{$memberRegidReward}'");

                            }

                        }else if($condition_rewardid == 0 && $rewardid > 2){

                            $rewardHistoryFields = array('userid'=>$userid, 'regid'=>$memberRegidReward, 'rewardid'=>$rewardid, 'membership_id'=> $membership_id_Reward, 'username'=> $memberRow['username'],'direct_sale'=> $direct_sale, 'group_sale'=>$group_sale, 'description'=>$description,'status' =>"fullfilled", 'user_ip'=>$user_ip, 'createtime'=>$createtime, 'createdate'=>$createdate);


                            // $rewardHistoryInsert = $db->insert('mlm_rewards_history',$rewardHistoryFields);

                            // if(!$rewardHistoryInsert){
                            //     echo "Error! Reward History is not updated";
                            // }

                            // $updateUser = $db->custom("UPDATE mlm_registrations SET rewardid = '$rewardid', incentive='$incentive', salary = '$salary' WHERE regid='{$memberRegidReward}'");

                        }
                    }
                } 
            }

            } 
        
        }
        $slr++;
        promotionCheck($memberRow['sponsor_id']); 
    }
    return;
}


promotionCheck("SKG660270"); // Sponsor ID






?>