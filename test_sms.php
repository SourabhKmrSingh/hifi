<?php 
include_once("inc_config.php");


$message = "Welcome To SK Realtech Family Your User ID- SK493487 Pass. SK9874 Please Visit www.skrealtech.co.in";
$result =  $api->sendSMS("7428420578", $message);

echo $result;



?>