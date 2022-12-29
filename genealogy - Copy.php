<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "genealogy";

if($_GET['regid'] != "")
{
	$regid = $validation->urlstring_validate($_GET['regid']);
}
$registerQueryResult = $db->view('*', 'mlm_registrations', 'regid', "and regid = '$regid'");
$registerRow = $registerQueryResult['result'][0];

$membership_id = $validation->db_field_validate($registerRow['membership_id']);
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
		<h1 CLASS="page-header">Genealogy (Team View)</h1>
	</div>
</div>

<div class="form-rows-custom mt-3">
<div class="row mb-3">
<div class="col-12">

<div class="tree w-mc">
<ul>
<li>
<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($registerRow['regid']); ?>">
	<p><?php echo $validation->db_field_validate($registerRow['username']); ?></p>
	<?php if($registerRow['imgName'] != "") { ?>
		<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($registerRow['imgName']); ?>" class="img-responsive" />
	<?php } else { ?>
		<img src="admin/images/user-icon.png" class="img-responsive" />
	<?php } ?>
	<p><?php echo $validation->db_field_validate($registerRow['membership_id']); ?></p>
</a>
<?php
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
		<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
		<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
		<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id2 = $validation->db_field_validate($treeRow['membership_id']);
$treeResult2 = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id2'", 'regid asc');
if($treeResult2['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult2['result'] as $treeRow2)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow2['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow2['username']); ?></p>
		<?php if($treeRow2['imgName'] != "") { ?>
		<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow2['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
		<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow2['membership_id']); ?></p>
		<?php if($treeRow2['status'] == "inactive") { ?>
		<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id3 = $validation->db_field_validate($treeRow2['membership_id']);
$treeResult3 = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id3'", 'regid asc');
if($treeResult3['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult3['result'] as $treeRow3)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow3['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow3['username']); ?></p>
		<?php if($treeRow3['imgName'] != "") { ?>
		<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow3['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
		<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow3['membership_id']); ?></p>
		<?php if($treeRow3['status'] == "inactive") { ?>
		<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id4 = $validation->db_field_validate($treeRow3['membership_id']);
$treeResult4 = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id4'", 'regid asc');
if($treeResult4['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult4['result'] as $treeRow4)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow4['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow4['username']); ?></p>
		<?php if($treeRow4['imgName'] != "") { ?>
		<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow4['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
		<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow4['membership_id']); ?></p>
		<?php if($treeRow4['status'] == "inactive") { ?>
		<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id5 = $validation->db_field_validate($treeRow4['membership_id']);
$treeResult5 = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id5'", 'regid asc');
if($treeResult5['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult5['result'] as $treeRow5)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow5['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow5['username']); ?></p>
		<?php if($treeRow5['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow5['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow5['membership_id']); ?></p>
		<?php if($treeRow5['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow5['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
	<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
	<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
<?php
$membership_id = $validation->db_field_validate($treeRow['membership_id']);
$treeResult = $db->view('membership_id,imgName,username,status,regid', 'mlm_registrations', 'regid', "and sponsor_id='$membership_id'", 'regid asc');
if($treeResult['num_rows'] >= 1)
{
?>
<ul>
<?php
foreach($treeResult['result'] as $treeRow)
{
?>
<li>
	<a href="genealogy.php?regid=<?php echo $validation->db_field_validate($treeRow['regid']); ?>">
		<p><?php echo $validation->db_field_validate($treeRow['username']); ?></p>
		<?php if($treeRow['imgName'] != "") { ?>
			<img src="<?php echo FILE_LOC.''.$validation->db_field_validate($treeRow['imgName']); ?>" class="img-responsive" />
		<?php } else { ?>
			<img src="admin/images/user-icon.png" class="img-responsive" />
		<?php } ?>
		<p><?php echo $validation->db_field_validate($treeRow['membership_id']); ?></p>
		<?php if($treeRow['status'] == "inactive") { ?>
			<p class="pending">Approval Pending</p>
		<?php } ?>
	</a>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
</ul>
</div>

</div>
</div>
</div>

</div>
</div>
</div>
</body>
</html>