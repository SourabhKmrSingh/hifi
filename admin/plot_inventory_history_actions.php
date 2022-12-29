<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "plot_inventory_followup";

$inventoryid = $validation->urlstring_validate($_GET['inventoryid']);

$q = $validation->urlstring_validate($_GET['q']);
if($q == "del")
{
	echo $validation->delete_permission();
	
	$historyid = $validation->urlstring_validate($_GET['historyid']);
	
	$delresult = $media->filedeletion('mlm_plots_inventory_history', 'historyid', $historyid, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
	$delresult2 = $media->filedeletion('mlm_plots_inventory_history', 'historyid', $historyid, 'fileName', FILE_LOC);
	
	$inventoryhistoryResult = $db->view('*', 'mlm_plots_inventory_history', 'historyid', "and historyid = '$historyid'");
	$inventoryhistoryRow = $inventoryhistoryResult['result'][0];
	$balance_amount = $inventoryhistoryRow['total_amount'] - $inventoryhistoryRow['amount'];
	
	$inventoryhistoryResult = $db->delete("mlm_plots_inventory_history", array('historyid'=>$historyid));
	if(!$inventoryhistoryResult)
	{
		$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
		header("Location: plot_inventory_history_view.php?inventoryid=$inventoryid");
		exit();
	}
	
	$fields2 = array('balance_amount'=>$balance_amount);
	$fields2['userid_updt'] = $userid;
	$fields2['modifytime'] = $createtime;
	$fields2['modifytime'] = $createdate;
	
	$inventoryResult = $db->update("mlm_plots_inventory", $fields2, array('inventoryid'=>$inventoryid));
	if(!$inventoryResult)
	{
		echo mysqli_error($connect);
		exit();
	}
	
	$_SESSION['success_msg'] = "{$inventoryhistoryResult} Record Deleted!";
	header("Location: plot_inventory_history_view.php?inventoryid=$inventoryid");
	exit();
}

if(isset($_POST['bulk_actions']) and $_POST['bulk_actions'] != "")
{
	$bulk_actions = $validation->urlstring_validate($_POST['bulk_actions']);
	$del_items = $_POST['del_items'];
	$historyids = array();
	if(empty($del_items))
	{
		$_SESSION['error_msg'] = "Please select atleast one row to perform action!";
		header("Location: plot_inventory_history_view.php?inventoryid=$inventoryid");
		exit();
	}
	if(isset($del_items) and $del_items != "")
	{
		foreach($del_items as $id)
		{
			if($bulk_actions == "delete")
			{
				array_push($historyids, "$id");
				
				$delresult = $media->filedeletion('mlm_plots_inventory_history', 'historyid', $id, 'imgName', IMG_MAIN_LOC, IMG_THUMB_LOC);
				$delresult2 = $media->filedeletion('mlm_plots_inventory_history', 'historyid', $id, 'fileName', FILE_LOC);
			}
			else if($bulk_actions == "active" || $bulk_actions == "inactive")
			{
				array_push($historyids, "$id");
			}
		}
		
		$historyids = implode(',', $historyids);
		
		if($bulk_actions == "delete")
		{
			$inventoryhistoryResult = $db->custom("DELETE from mlm_plots_inventory_history where FIND_IN_SET(`historyid`, '$historyids')");
			if(!$inventoryhistoryResult)
			{
				$_SESSION['error_msg'] = "Error Occurred. Please try again!!!";
				header("Location: plot_inventory_history_view.php?inventoryid=$inventoryid");
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Deleted!";
			header("Location: plot_inventory_history_view.php?inventoryid=$inventoryid");
			exit();
		}
		else if($bulk_actions == "active" || $bulk_actions == "inactive")
		{
			$inventoryhistoryResult = $db->custom("UPDATE mlm_plots_inventory_history SET status='$bulk_actions' where FIND_IN_SET(`historyid`, '$historyids')");
			if(!$inventoryhistoryResult)
			{
				echo mysqli_error($connect);
				exit();
			}
			$affected_rows = $connect->affected_rows;
			
			$_SESSION['success_msg'] = "{$affected_rows} Record(s) Updated!";
			header("Location: plot_inventory_history_view.php?inventoryid=$inventoryid");
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
	
	header("Location: plot_inventory_history_view.php?inventoryid=$inventoryid&$fields_string");
	exit();
}
?>