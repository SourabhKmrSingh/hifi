<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "plot_inventory_followup";

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
?>