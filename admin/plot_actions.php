<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "plot";

$q = $validation->urlstring_validate($_GET['q']);
if($q == "del")
{
	echo $validation->delete_permission();
	
	$plotid = $validation->urlstring_validate($_GET['plotid']);
	
	$delresult = $media->filedeletion('mlm_plots', 'plotid', $plotid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
	$delresult2 = $media->filedeletion('mlm_plots', 'plotid', $plotid, 'fileName', FILE_LOC);

	$plotQueryResult = $db->delete("mlm_plots", array('plotid'=>$plotid));
	if(!$plotQueryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: plot_view.php");
		exit();
	}

	$_SESSION['success_msg'] = "{$plotQueryResult} Record Deleted!";
	header("Location: plot_view.php");
	exit();
}

if(isset($_POST['bulk_actions']) and $_POST['bulk_actions'] != "")
{
	$bulk_actions = $validation->urlstring_validate($_POST['bulk_actions']);
	$del_items = $_POST['del_items'];
	$plotids = array();
	if(empty($del_items))
	{
		$_SESSION['error_msg'] = "Please select atleast one row to perform action!";
		header("Location: plot_view.php");
		exit();
	}
	if(isset($del_items) and $del_items != "")
	{
		foreach($del_items as $id)
		{
			if($bulk_actions == "delete")
			{
				array_push($plotids, "$id");
				
				$delresult = $media->filedeletion('mlm_plots', 'plotid', $id, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
				$delresult2 = $media->filedeletion('mlm_plots', 'plotid', $id, 'fileName', FILE_LOC);
			}
			else if($bulk_actions == "active" || $bulk_actions == "inactive")
			{
				array_push($plotids, "$id");
			}
		}
		
		$plotids = implode(',', $plotids);
		
		if($bulk_actions == "delete")
		{
			$plotQueryResult = $db->custom("DELETE from mlm_plots where FIND_IN_SET(`plotid`, '$plotids')");
			if(!$plotQueryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: plot_view.php");
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Deleted!";
			header("Location: plot_view.php");
			exit();
		}
		else if($bulk_actions == "active" || $bulk_actions == "inactive")
		{
			$plotQueryResult = $db->custom("UPDATE mlm_plots SET status='$bulk_actions' where FIND_IN_SET(`plotid`, '$plotids')");
			if(!$plotQueryResult)
			{
				echo mysqli_error($connect);
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Updated!";
			header("Location: plot_view.php");
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
	
	header("Location: plot_view.php?$fields_string");
	exit();
}
?>