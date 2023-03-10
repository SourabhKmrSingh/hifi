<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "map";

$q = $validation->urlstring_validate($_GET['q']);
if($q == "del")
{
	echo $validation->delete_permission();
	
	$mapid = $validation->urlstring_validate($_GET['mapid']);
	
	$delresult = $media->filedeletion('mlm_maps', 'mapid', $mapid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
	$delresult2 = $media->filedeletion('mlm_maps', 'mapid', $mapid, 'fileName', FILE_LOC);

	$mapQueryResult = $db->delete("mlm_maps", array('mapid'=>$mapid));
	if(!$mapQueryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: map_view.php");
		exit();
	}

	$_SESSION['success_msg'] = "{$mapQueryResult} Record Deleted!";
	header("Location: map_view.php");
	exit();
}

if(isset($_POST['bulk_actions']) and $_POST['bulk_actions'] != "")
{
	$bulk_actions = $validation->urlstring_validate($_POST['bulk_actions']);
	$del_items = $_POST['del_items'];
	$mapids = array();
	if(empty($del_items))
	{
		$_SESSION['error_msg'] = "Please select atleast one row to perform action!";
		header("Location: map_view.php");
		exit();
	}
	if(isset($del_items) and $del_items != "")
	{
		foreach($del_items as $id)
		{
			if($bulk_actions == "delete")
			{
				array_push($mapids, "$id");
				
				$delresult = $media->filedeletion('mlm_maps', 'mapid', $id, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
				$delresult2 = $media->filedeletion('mlm_maps', 'mapid', $id, 'fileName', FILE_LOC);
			}
			else if($bulk_actions == "active" || $bulk_actions == "inactive")
			{
				array_push($mapids, "$id");
			}
		}
		
		$mapids = implode(',', $mapids);
		
		if($bulk_actions == "delete")
		{
			$mapQueryResult = $db->custom("DELETE from mlm_maps where FIND_IN_SET(`mapid`, '$mapids')");
			if(!$mapQueryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: map_view.php");
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Deleted!";
			header("Location: map_view.php");
			exit();
		}
		else if($bulk_actions == "active" || $bulk_actions == "inactive")
		{
			$mapQueryResult = $db->custom("UPDATE mlm_maps SET status='$bulk_actions' where FIND_IN_SET(`mapid`, '$mapids')");
			if(!$mapQueryResult)
			{
				echo mysqli_error($connect);
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Updated!";
			header("Location: map_view.php");
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
	
	header("Location: map_view.php?$fields_string");
	exit();
}
?>