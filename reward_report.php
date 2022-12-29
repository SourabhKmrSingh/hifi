<?php
include_once("inc_config.php");
include_once("login_user_check.php");
include_once("inc_downline.php");

$_SESSION['active_menu'] = "rewardReport";
$membership_id = $_SESSION['mlm_membership_id'];

$registerResult = $db->view('first_name,last_name,membership_id,sponsor_id,regid,members,wallet_total,wallet_money, status, rewardid', 'mlm_registrations', 'regid', "and regid = '$regid'");
$registerRow = $registerResult['result'][0];

$rewardid = $registerRow['rewardid'];

$rewardResult = $db->view("*", "mlm_rewards", 'rewardid', " and rewardid = '$rewardid'");
$rewardRow = $rewardResult['result'][0];

$nextRewardResult = $db->view('*', "mlm_rewards", "rewardid", " and rewardid > '$rewardid' and status ='active'", "rewardid asc", 1);

$nextRewardRow = $nextRewardResult['result'][0];

$direct_sale = $nextRewardRow['direct_sale'];
$group_sale = $nextRewardRow['group_sale'];
$condition_rewardid = $nextRewardRow['condition_rewardid'];
$condition_members = $nextRewardRow['condition_members'];
$condition_legs = $nextRewardRow['condition_legs'];



$legs_data = array();
$designationData = array();
$leg = 1;
$memberparent = '';
$previousParent = array();
$directSale_total = 0;

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
    global $db, $leg, $designationData, $membership_id;
    $treeResult = $db->view('membership_id,imgName,username,status', 'mlm_registrations', 'regid', "and sponsor_id='$parent'", 'regid asc');

    if($treeResult['num_rows'] >= 1)
    {
        foreach($treeResult['result'] as $treeRow)
        {
            $membership_id_des = $treeRow['membership_id'];

            $rewardResult = $db->view('rewardid, membership_id','mlm_registrations','regid'," and rewardid = '$required_rewardid' and membership_id != '$membership_id_des' and membership_id != '$membership_id' and status = 'active'");

            if($rewardResult['num_rows'] >= 1){
                print_r($rewardResult);

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


$leg = 1;
$legs_data = array();
$memberparent = '';
$previousParent = array();

$group_sale_data = group_sale_leg_wise($registerRow['membership_id']);

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


$plotInventoryResult = $db->view('inventoryid','mlm_plots_inventory','inventoryid', " and sponsor_id = '$membership_id' and record_check = '1'");

$directSale_total = $plotInventoryResult['num_rows'];

$leg = 1;
$designationData = array();

if($condition_rewardid != "0"){

   
    $data1 = fetch_designations($memberRow['membership_id'], $condition_rewardid);
    $total_members_data = 0;
    foreach($data1 as $memberCount){
        $total_members_data += $memberCount['total_members'];
    }

    $CconditionRewardResult = $db->view("*", "mlm_rewards", 'rewardid', " and rewardid = '$condition_rewardid'");

    $CconditionRewardRow = $CconditionRewardResult['result'][0];

  
}



// // Data Testing

// echo $nextRewardRow['title'] . "<br>";

// echo  "Group Sale:"  . $group_sale . " -  "  . $group_sale_total . "<br>";

// echo "Direct Sale:" . $direct_sale . " -  " . $directSale_total . "<br>";

// echo "Total Members" . $total_members_data . " - Cond." . $condition_members . "<br>";

// echo "Total Legs" . count($data1) . " - Cond." . $condition_legs . "<br><br>";

// exit();
?>
<!DOCTYPE html>
<html LANG="en">

<head>
	<?php include_once("inc_title.php"); ?>
	<?php include_once("inc_files.php"); ?>
	<style>
		.card_custom {
			box-shadow: 0 2px 20px rgba(65, 131, 215, 0.22), 0 2px 11px rgba(65, 131, 215, 0.15);
			background: #FFFFFF;
		}
	</style>
</head>

<body>
	<div ID="wrapper">
		<?php include_once("inc_header.php"); ?>
		<div ID="page-wrapper">
			<div CLASS="container-fluid">
				<div CLASS="row">
					<div CLASS="col-lg-12">
						<h1 CLASS="page-header">Reward Report</h1>
					</div>
				</div>


				<div CLASS="row">
					<div CLASS="col-lg-4 col-md-6 mb-3 mb-md-0 ">
						<div class="card overflow-hidden" style="max-height: 165px;">
							<div class="card-body">
								<div class="row">
									<div class="col">
										<h6 class=""><?php echo $validation->db_field_validate($registerRow['first_name'] . ' ' . $registerRow['last_name']); ?></h6>
									
										<p class="mb-0">
											<span class="text-muted">Current Designation: </span><?php echo $validation->db_field_validate(ucfirst($rewardRow['title'])); ?>&nbsp;&nbsp;&nbsp;

										</p>

									</div>
									
								</div>
							</div>
						</div>
					</div><br>


                    <?php if($nextRewardResult['num_rows'] >= 1){ ?>
                                        
                    <div class="table-responsive">
                    <table class="table table-striped table-view" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Next Designation</th>
                            <th>Required Direct Sales</th>
                            <th>Required Group Sales</th>
                            <th>Current Direct Sales</th>
                            <th>Current Group Sales</th>
                            <?php if($condition_rewardid != "0"){?>
                            <th>Required Qualify In Team</th>
                            <th>Current Qualify In Team</th>
                            <?php }?>
                        </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center has-row-actions">
                                <td data-label="Next Designation - " class='p-4'><?php echo $validation->db_field_validate($nextRewardRow['title']);?></td>
                                <td data-label="Required Direct Sales - " class='p-4'><?php echo $validation->input_validate($nextRewardRow['direct_sale']);?></td>
                                <td data-label="Required Group Sales - " class='p-4'><?php echo $validation->input_validate($nextRewardRow['group_sale']);?></td>
                                <td data-label="Current Direct Sales - " class='p-4'><?php echo $validation->input_validate($directSale_total);?></td>
                                <td data-label="Current Group Sales - " class='p-4'><?php echo $validation->input_validate($group_sale_total);?></td>
                                <?php if($condition_rewardid != "0") { ?>
                                <td data-label="Required Qualify In Team - " class='p-4'><?php echo $condition_rewardid != "0" ? $validation->input_validate($CconditionRewardRow['title']) . " ("  . $nextRewardRow['condition_members']  . ")"  . ($nextRewardRow['condition_legs'] > 1 ? "<br /> from {$nextRewardRow['condition_legs']} diffrent legs" : "") : "";?></td>
                                <td data-label="Current Qualify In Team - " class='p-4'><?php echo $validation->input_validate($total_members_data);?></td>
                                <?php }?>
                            </tr>
                            
                        </tbody>
                    </table>
                    </div>

                    <?php }?>
	
				</div>
			</div>
		</div>
	</div>
	
</body>

</html>