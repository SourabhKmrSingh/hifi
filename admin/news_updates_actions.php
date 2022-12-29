<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "news_updates";

$q = $validation->urlstring_validate($_GET['q']);
if($q == "del")
{
	echo $validation->delete_permission();
	
	$newsid = $validation->urlstring_validate($_GET['newsid']);
	
	$enquiryQueryResult = $db->delete("mlm_news_updates", array('newsid'=>$newsid));
	if(!$enquiryQueryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: news_updates_view.php");
		exit();
	}

	$_SESSION['success_msg'] = "{$enquiryQueryResult} Record Deleted!";
	header("Location: news_updates_view.php");
	exit();
}

if(isset($_POST['bulk_actions']) and $_POST['bulk_actions'] != "")
{
	$bulk_actions = $validation->urlstring_validate($_POST['bulk_actions']);
	$del_items = $_POST['del_items'];
	$newsids = array();
	if(empty($del_items))
	{
		$_SESSION['error_msg'] = "Please select atleast one row to perform action!";
		header("Location: news_updates_view.php");
		exit();
	}
	if(isset($del_items) and $del_items != "")
	{
		foreach($del_items as $id)
		{
			array_push($newsids, "$id");
		}
		
		$newsids = implode(',', $newsids);
		
		if($bulk_actions == "delete")
		{
			$enquiryQueryResult = $db->custom("DELETE from mlm_news_updates where FIND_IN_SET(`newsid`, '$newsids')");
			if(!$enquiryQueryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: news_updates_view.php");
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Deleted!";
			header("Location: news_updates_view.php");
			exit();
		}
		else if($bulk_actions == "inactive" || $bulk_actions == "active")
		{
			$enquiryQueryResult = $db->custom("UPDATE mlm_news_updates SET status='$bulk_actions' where FIND_IN_SET(`newsid`, '$newsids')");
			if(!$enquiryQueryResult)
			{
				echo mysqli_error($connect);
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Updated!";
			header("Location: news_updates_view.php");
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
	
	header("Location: news_updates_view.php?$fields_string");
	exit();
}
?>