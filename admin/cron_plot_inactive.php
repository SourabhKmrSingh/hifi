<?php 
include_once("inc_config.php");

$plotInventoryResult = $db->view("*", "mlm_plots_inventory", "inventoryid", " and status = 'active' and payment_type = 'Token'");

if($plotInventoryResult['num_rows'] >= 1){
    
    foreach($plotInventoryResult['result'] as $plotInventoryRow){


        print_r($plotInventoryRow);

        $inventoryid = $plotInventoryRow['inventoryid'];

        $plotInventoryHistoryResult = $db->view("*", 'mlm_plots_inventory_history', 'historyid', " and inventoryid = '$inventoryid'", 'historyid desc');

        $plotInventoryHistoryRow = $plotInventoryHistoryResult['result'][0];

        if($plotInventoryHistoryRow['payment_type'] == "Token"){


            $now = new DateTime();
            $ago = new DateTime("{$plotInventoryRow['createdate']} {$plotInventoryRow['createtime']}");
            $diff = $now->diff($ago);

            echo "<pre>";
            print_r($diff);

            if($diff->days >= 7){
                print_r($plotInventoryRow);
                
                $db->update("mlm_plots_inventory", array("status" => 'inactive'), array("inventoryid"=> $plotInventoryRow['inventoryid']));
            }

        }

    }

}

?>