<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "block";

$q = $validation->urlstring_validate($_GET['q']);
if($q == "del")
{
	echo $validation->delete_permission();
	
	$blockid = $validation->urlstring_validate($_GET['blockid']);
	
	$delresult = $media->filedeletion('mlm_blocks', 'blockid', $blockid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
	$delresult2 = $media->filedeletion('mlm_blocks', 'blockid', $blockid, 'fileName', FILE_LOC);

	$blockQueryResult = $db->delete("mlm_blocks", array('blockid'=>$blockid));
	if(!$blockQueryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: block_view.php");
		exit();
	}

	$_SESSION['success_msg'] = "{$blockQueryResult} Record Deleted!";
	header("Location: block_view.php");
	exit();
}

if(isset($_POST['bulk_actions']) and $_POST['bulk_actions'] != "")
{
	$bulk_actions = $validation->urlstring_validate($_POST['bulk_actions']);
	$del_items = $_POST['del_items'];
	$blockids = array();
	if(empty($del_items))
	{
		$_SESSION['error_msg'] = "Please select atleast one row to perform action!";
		header("Location: block_view.php");
		exit();
	}
	if(isset($del_items) and $del_items != "")
	{
		foreach($del_items as $id)
		{
			if($bulk_actions == "delete")
			{
				array_push($blockids, "$id");
				
				$delresult = $media->filedeletion('mlm_blocks', 'blockid', $id, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
				$delresult2 = $media->filedeletion('mlm_blocks', 'blockid', $id, 'fileName', FILE_LOC);
			}
			else if($bulk_actions == "active" || $bulk_actions == "inactive")
			{
				array_push($blockids, "$id");
			}
		}
		
		$blockids = implode(',', $blockids);
		
		if($bulk_actions == "delete")
		{
			$blockQueryResult = $db->custom("DELETE from mlm_blocks where FIND_IN_SET(`blockid`, '$blockids')");
			if(!$blockQueryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: block_view.php");
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Deleted!";
			header("Location: block_view.php");
			exit();
		}
		else if($bulk_actions == "active" || $bulk_actions == "inactive")
		{
			$blockQueryResult = $db->custom("UPDATE mlm_blocks SET status='$bulk_actions' where FIND_IN_SET(`blockid`, '$blockids')");
			if(!$blockQueryResult)
			{
				echo mysqli_error($connect);
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Updated!";
			header("Location: block_view.php");
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
	
	header("Location: block_view.php?$fields_string");
	exit();
}
?>