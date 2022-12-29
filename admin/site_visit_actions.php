<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "sitevisit";

$q = $validation->urlstring_validate($_GET['q']);
if($q == "del")
{
	echo $validation->delete_permission();
	
	$sitevisitid = $validation->urlstring_validate($_GET['sitevisitid']);
	
	$enquiryQueryResult = $db->delete("mlm_site_visit", array('sitevisitid'=>$sitevisitid));
	if(!$enquiryQueryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: site_visit_view.php");
		exit();
	}

	$_SESSION['success_msg'] = "{$enquiryQueryResult} Record Deleted!";
	header("Location: site_visit_view.php");
	exit();
}

if(isset($_POST['bulk_actions']) and $_POST['bulk_actions'] != "")
{
	$bulk_actions = $validation->urlstring_validate($_POST['bulk_actions']);
	$del_items = $_POST['del_items'];
	$sitevisitids = array();
	if(empty($del_items))
	{
		$_SESSION['error_msg'] = "Please select atleast one row to perform action!";
		header("Location: site_visit_view.php");
		exit();
	}
	if(isset($del_items) and $del_items != "")
	{
		foreach($del_items as $id)
		{
			array_push($sitevisitids, "$id");
		}
		
		$sitevisitids = implode(',', $sitevisitids);
		
		if($bulk_actions == "delete")
		{
			$enquiryQueryResult = $db->custom("DELETE from mlm_site_visit where FIND_IN_SET(`sitevisitid`, '$sitevisitids')");
			if(!$enquiryQueryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: site_visit_view.php");
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Deleted!";
			header("Location: site_visit_view.php");
			exit();
		}
		else if($bulk_actions == "open" || $bulk_actions == "in-process" || $bulk_actions == "rejected" || $bulk_actions == "closed")
		{
			$enquiryQueryResult = $db->custom("UPDATE mlm_site_visit SET status='$bulk_actions' where FIND_IN_SET(`sitevisitid`, '$sitevisitids')");
			if(!$enquiryQueryResult)
			{
				echo mysqli_error($connect);
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Updated!";
			header("Location: site_visit_view.php");
			exit();
		}
	}
}
else
{
	$fields = $_POST;
	
	foreach($fields as $key=>$value)
	{
		$fields_string .= $key.'='.$value.'&';
	}
	rtrim($fields_string, '&');
	$fields_string = str_replace("bulk_actions=&", "", $fields_string);
	$fields_string = substr($fields_string, 0, -1);
	
	header("Location: site_visit_view.php?$fields_string");
	exit();
}
?>