<?php

include_once("inc_config.php");

include_once("login_user_check.php");



$_SESSION['active_menu'] = "genealogy";



if($_GET['regid'] != "")

{

	$regid = $validation->urlstring_validate($_GET['regid']);

}

$Members = array();
$activeMembers = array();

function getAllMembersDown($parent)
{
	global $db,$Members, $activeMembers;
	
	$treeResult = $db->view('membership_id,imgName,username,status', 'mlm_registrations', 'regid', "and sponsor_id='$parent'", 'regid asc');
	if($treeResult['num_rows'] >= 1)
	{
		foreach($treeResult['result'] as $treeRow)
		{
            array_push($Members, $treeRow['membership_id']); 
			if($treeRow['status'] == 'active'){
				array_push($activeMembers,$treeRow['membership_id']);
			}
			getAllMembersDown($treeRow['membership_id']);
		}
	}
	return;
}

$error = "";


if($_POST['membership_id'] != ''){

	$searchMembership_id = $validation->input_validate($_POST['membership_id']);

	$checkUser = $db->view('membership_id', 'mlm_registrations', 'regid', "and regid = '$regid'");

	$checkUserMembership = $checkUser['result'][0]['membership_id'];

	

	

	

	
	$searchResult = $db->view("regid", "mlm_registrations",'regid', " and membership_id = '$searchMembership_id'");

	if($searchResult['num_rows'] >= 1){
		if(in_array($searchMembership_id, $Members) || $checkUserMembership == $searchMembership_id){
			$regid = $searchResult['result'][0]['regid'];
			$Members = array();
			$activeMembers = array();	
		}else{
			$error = "This Member is not in your Downline.";
		}
	}else{
		$error = "Member Not Found";
	}
}


$registerQueryResult = $db->view('*', 'mlm_registrations', 'regid', "and regid = '$regid'");

$registerRow = $registerQueryResult['result'][0];



$membership_id = $validation->db_field_validate($registerRow['membership_id']);
$orderResult = $db->view("*",'rb_orders','orderid', " and membership_id = '$membership_id'");

if($membership_id != ""){
	
	getAllMembersDown($membership_id);

}
if($orderResult['num_rows'] >= 1){
	
	$orderRow = $orderResult['result'][0];
	if($orderRow['status'] ==  'inreview'){
		$box_color = "box_token";
	}else if($orderRow['status'] == 'verified'){
		$box_color = "box_booked";
	}
}
else
{
	$box_color = "box_token";
	$booking_status = "Available";
}
?>

<!DOCTYPE html>

<html LANG="en">

<head>

<?php include_once("inc_title.php"); ?>

<?php include_once("inc_files.php"); ?>

</head>

<body>

<div ID="wrapper">

<?php include_once("inc_header.php"); ?>

<div ID="page-wrapper">

<div CLASS="container-fluid">

<div CLASS="row">

	<div CLASS="col-lg-12">

		<h1 CLASS="page-header">My Group Tree</h1>

	</div>

</div>

<div class="row">
	<div class="col-sm-12">
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class='float-right'>
			<div class="d-flex align-items-center">
				<input type="text" placeholder="Enter Membership ID" class='form-control' id='membership_id' name ='membership_id' value='<?php echo $_POST['membership_id'];?>'>
				<input type="submit" value="Search" class='btn btn-primary btn-sm' style='min-width: 3.5rem;'>
			</div>
			<small class= 'text-danger'><i><?php echo $error !="" ? $error : "";?></i></small>


			<p class='text-right mt-3 '>Total Team: <span class='font-weight-bold'><?php echo count($Members);?></span></p>
		</form>
	</div>
</div>

<div class="form-rows-custom mt-3">

<div class="row mb-3">

<div class="col-12">

<p>Note: Click on the member's box to view it's list</p>

<div class="body genealogy-body genealogy-scroll">

<div class="genealogy-tree">

<?php
$FILE_LOC = FILE_LOC;
$slr =0;
function getAllDownlines($parent)

{
    global $db,$slr, $FILE_LOC, $validation;
    $treeResult = $db->view('membership_id,imgName,username,status', 'mlm_registrations', 'regid', "and sponsor_id='$parent'", 'regid asc');
	if($treeResult['num_rows'] >= 1)
	{
		if($slr == 0){
			$slr = 1;
			echo "<ul class='active'>";
		}else{
			echo "<ul>";
		}
		foreach($treeResult['result'] as $treeRow)
		{

						
			$membership_id = $treeRow['membership_id'];
			
			$box_color = "box_token";
			$booking_status = "Available";

			echo "<li>";
				echo "<a href='javascript:void(0);'>";
					echo "<div class='member-view-box {$box_color}'>";
						echo "<div class='member-image'>";
							if($treeRow['imgName'] != "") { 
								$image = $FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']);
								echo "<img src='{$image}' class='img-responsive' />";
							} else { 
								echo "<img src='admin/images/user-icon.png' class='img-responsive' />";
							} 
						echo "<div class='member-details'>";
						$membership_id = $validation->db_field_validate($treeRow['membership_id']);
						$username =$validation->db_field_validate($treeRow['username']);
						echo "<p>{$membership_id}</p>";
						echo "<p>{$username}</p>";
						if($treeRow['status'] == "inactive") {
							echo "<p class='pending text-danger'>Inactive</p>";
						}
						echo "</div>
						</div>
					</div>
				</a>";
				$slr++;
			getAllDownlines($treeRow['membership_id']);
			echo "</li>";
		}
		echo "</ul>";
	}
}
?>
<ul>
	<li>
		<a href="javascript:void(0);">
			<div class="member-view-box <?php echo $box_color; ?>">
				<div class="member-image">
					<?php if($registerRow['imgName'] != "") { ?>
						<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($registerRow['imgName']); ?>" class="img-responsive" />
					<?php } else { ?>
						<img src="admin/images/user-icon.png" class="img-responsive" />
					<?php } ?>
					<div class="member-details">
						<p><?php echo $validation->db_field_validate($registerRow['username']); ?></p>
						<p><?php echo $validation->db_field_validate($registerRow['membership_id']); ?></p>
						<?php 
							if($registerRow['status'] == "inactive") {
								echo "<p class='pending text-danger'>Inactive</p>";
							}
						?>
					</div>
				</div>
			</div>
		</a>
		<?php getAllDownlines($registerRow['membership_id']);?>
	</li>
</ul>


</div>

</div>



</div>

</div>

</div>



</div>

</div>

</div>

</body>

</html>