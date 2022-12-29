<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "project";

$q = $validation->urlstring_validate($_GET['q']);
if($q == "del")
{
	echo $validation->delete_permission();
	
	$projectid = $validation->urlstring_validate($_GET['projectid']);
	
	$delresult = $media->filedeletion('mlm_projects', 'projectid', $projectid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
	$delresult2 = $media->filedeletion('mlm_projects', 'projectid', $projectid, 'fileName', FILE_LOC);

	$projectQueryResult = $db->delete("mlm_projects", array('projectid'=>$projectid));
	if(!$projectQueryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: project_view.php");
		exit();
	}

	$_SESSION['success_msg'] = "{$projectQueryResult} Record Deleted!";
	header("Location: project_view.php");
	exit();
}

if(isset($_POST['bulk_actions']) and $_POST['bulk_actions'] != "")
{
	$bulk_actions = $validation->urlstring_validate($_POST['bulk_actions']);
	$del_items = $_POST['del_items'];
	$projectids = array();
	if(empty($del_items))
	{
		$_SESSION['error_msg'] = "Please select atleast one row to perform action!";
		header("Location: project_view.php");
		exit();
	}
	if(isset($del_items) and $del_items != "")
	{
		foreach($del_items as $id)
		{
			if($bulk_actions == "delete")
			{
				array_push($projectids, "$id");
				
				$delresult = $media->filedeletion('mlm_projects', 'projectid', $id, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
				$delresult2 = $media->filedeletion('mlm_projects', 'projectid', $id, 'fileName', FILE_LOC);
			}
			else if($bulk_actions == "active" || $bulk_actions == "inactive")
			{
				array_push($projectids, "$id");
			}
		}
		
		$projectids = implode(',', $projectids);
		
		if($bulk_actions == "delete")
		{
			$projectQueryResult = $db->custom("DELETE from mlm_projects where FIND_IN_SET(`projectid`, '$projectids')");
			if(!$projectQueryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: project_view.php");
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Deleted!";
			header("Location: project_view.php");
			exit();
		}
		else if($bulk_actions == "active" || $bulk_actions == "inactive")
		{
			$projectQueryResult = $db->custom("UPDATE mlm_projects SET status='$bulk_actions' where FIND_IN_SET(`projectid`, '$projectids')");
			if(!$projectQueryResult)
			{
				echo mysqli_error($connect);
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Updated!";
			header("Location: project_view.php");
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
	
	header("Location: project_view.php?$fields_string");
	exit();
}
?>