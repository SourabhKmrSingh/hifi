<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "plot_inventory_followup";

$q = $validation->urlstring_validate($_GET['q']);
if($q == "del")
{
	echo $validation->delete_permission();
	
	$inventoryid = $validation->urlstring_validate($_GET['inventoryid']);
	
	$delresult = $media->filedeletion('mlm_plots_inventory', 'inventoryid', $inventoryid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
	$delresult2 = $media->filedeletion('mlm_plots_inventory', 'inventoryid', $inventoryid, 'fileName', FILE_LOC);

	$inventoryQueryResult = $db->delete("mlm_plots_inventory", array('inventoryid'=>$inventoryid));
	if(!$inventoryQueryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: plot_inventory_view.php");
		exit();
	}
	
	$inventoryhistoryResult = $db->delete("mlm_plots_inventory_history", array('inventoryid'=>$inventoryid));
	if(!$inventoryhistoryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: plot_inventory_view.php");
		exit();
	}

	$_SESSION['success_msg'] = "{$inventoryQueryResult} Record Deleted!";
	header("Location: plot_inventory_view.php");
	exit();
}

if(isset($_POST['bulk_actions']) and $_POST['bulk_actions'] != "")
{
	$bulk_actions = $validation->urlstring_validate($_POST['bulk_actions']);
	$del_items = $_POST['del_items'];
	$inventoryids = array();
	if(empty($del_items))
	{
		$_SESSION['error_msg'] = "Please select atleast one row to perform action!";
		header("Location: plot_inventory_view.php");
		exit();
	}
	if(isset($del_items) and $del_items != "")
	{
		foreach($del_items as $id)
		{
			if($bulk_actions == "delete")
			{
				array_push($inventoryids, "$id");
				
				$delresult = $media->filedeletion('mlm_plots_inventory', 'inventoryid', $id, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
				$delresult2 = $media->filedeletion('mlm_plots_inventory', 'inventoryid', $id, 'fileName', FILE_LOC);
			}
			else if($bulk_actions == "active" || $bulk_actions == "inactive")
			{
				array_push($inventoryids, "$id");
			}
		}
		
		$inventoryids = implode(',', $inventoryids);
		
		if($bulk_actions == "delete")
		{
			$inventoryQueryResult = $db->custom("DELETE from mlm_plots_inventory where FIND_IN_SET(`inventoryid`, '$inventoryids')");
			if(!$inventoryQueryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: plot_inventory_view.php");
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$inventoryhistoryResult = $db->custom("DELETE from mlm_plots_inventory_history where FIND_IN_SET(`inventoryid`, '$inventoryids')");
			if(!$inventoryhistoryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: plot_inventory_view.php");
				exit();
			}
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Deleted!";
			header("Location: plot_inventory_view.php");
			exit();
		}
		else if($bulk_actions == "active" || $bulk_actions == "inactive")
		{
			$inventoryQueryResult = $db->custom("UPDATE mlm_plots_inventory SET status='$bulk_actions' where FIND_IN_SET(`inventoryid`, '$inventoryids')");
			if(!$inventoryQueryResult)
			{
				echo mysqli_error($connect);
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Updated!";
			header("Location: plot_inventory_view.php");
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
	
	header("Location: plot_inventory_view.php?$fields_string");
	exit();
}
?>