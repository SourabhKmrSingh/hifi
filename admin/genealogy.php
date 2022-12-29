<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "register";

$regid = $validation->urlstring_validate($_GET['regid']);
$registerQueryResult = $db->view('*', 'mlm_registrations', 'regid', "and regid = '$regid'");
$registerRow = $registerQueryResult['result'][0];

$membership_id = $validation->db_field_validate($registerRow['membership_id']);

$orderResult = $db->view("*",'rb_orders','orderid', " and membership_id = '$membership_id'");
if($orderResult['num_rows'] >= 1){
	
	$orderRow = $orderResult['result'][0];
	if($orderRow['status'] == 'inreview'){
		$box_color = "box_token";
	}
	else if($orderRow['status'] == 'verified'){
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
		<h1 CLASS="page-header">Direct Tree (Team View)</h1>
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
					echo "<div class='member-view-box  {$box_color}'>";
						echo "<div class='member-image'>";
							if($treeRow['imgName'] != "") { 
								$image = $FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']);
								echo "<img src='{$image}' class='img-responsive' />";
							} else { 
								echo "<img src='images/user-icon.png' class='img-responsive' />";
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
						<img src="images/user-icon.png" class="img-responsive" />
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