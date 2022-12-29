<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "sitevisit";

if(isset($_GET['mode']))
{
	$mode = $validation->urlstring_validate($_GET['mode']);
}
else
{
	$_SESSION['error_msg'] = "There is a problem. Please Try Again!";
	header("Location: site_visit_view.php");
	exit();
}

if($mode == "edit")
{
	echo $validation->update_permission();
}
else
{
	echo $validation->write_permission();
}

if($mode == "edit")
{
	if(isset($_GET['sitevisitid']))
	{
		$sitevisitid = $validation->urlstring_validate($_GET['sitevisitid']);
		if($_SESSION['search_filter'] != "")
		{
			$search_filter = "?".$_SESSION['search_filter'];
		}
	}
	else
	{
		$_SESSION['error_msg'] = "There is a problem. Please Try Again!";
		header("Location: site_visit_view.php");
		exit();
	}
}


$first_name = $validation->input_validate($_POST['first_name']);
$last_name = $validation->input_validate($_POST['last_name']);
$email = $validation->input_validate($_POST['email']);
$mobile = $validation->input_validate($_POST['mobile']);
$site_visit_time = $validation->input_validate($_POST['site_visit_time']);
$site_visit_date = $validation->input_validate($_POST['site_visit_date']);
$total_person = $validation->input_validate($_POST['total_person']);
$pickup_location = $validation->input_validate($_POST['pickup_location']);
$remarks = $validation->input_validate($_POST['remarks']);

$user_ip_array = ($_POST['user_ip']!='') ? explode(", ", $validation->input_validate($_POST['user_ip'])) : array();
array_push($user_ip_array, $user_ip);
$user_ip_array = array_unique($user_ip_array);
$user_ip = implode(", ", $user_ip_array);

$fields = array('first_name'=>$first_name, 'last_name'=>$last_name, 'email'=>$email, 'mobile'=>$mobile, 'site_visit_time'=> $site_visit_time, 'site_visit_date'=> $site_visit_date, 'total_person' => $total_person, 'pickup_location'=> $pickup_location, 'remarks'=> $remarks, 'user_ip'=>$user_ip);


if($mode == "insert")
{
	$fields['userid'] = $userid;
	$fields['createtime'] = $createtime;
	$fields['createdate'] = $createdate;
	
	$enquiryQueryResult = $db->insert("mlm_site_visit", $fields);
	if(!$enquiryQueryResult)
	{
		echo mysqli_error($connect);
		exit();
	}
	
	$_SESSION['success_msg'] = "Record Added!";
	header("Location: site_visit_view.php");
	exit();
}
else if($mode == "edit")
{
	$fields['userid_updt'] = $userid;
	$fields['modifytime'] = $createtime;
	$fields['modifydate'] = $createdate;
	
	$enquiryQueryResult = $db->update("mlm_site_visit", $fields, array('sitevisitid'=>$sitevisitid));
	if(!$enquiryQueryResult)
	{
		echo mysqli_error($connect);
		exit();
	}
	
	$_SESSION['success_msg'] = "Record Updated!";
	header("Location: site_visit_view.php$search_filter");
	exit();
}
?>