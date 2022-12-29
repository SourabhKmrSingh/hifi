<?php

class api

{

	public function sendSMS($recipient_no, $message)

	{

	
		$content = array(
			'user' => 'rudraahousing',
			'password' => 'J1ASFUV5',
			'msisdn' => $recipient_no,
			'sid' => 'MSVERM',
			'msg' => $message,
			'fl'=>0,
			'gwid'=>2
		);

		

		$apiUrl = "http://skietsocial.in/vendorsms/pushsms.aspx?";

		foreach($content as $key => $val)

		{

			$apiUrl .= $key.'='.rawurlencode($val).'&';

		}

		$apiUrl = rtrim($apiUrl, "&");

		

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $apiUrl);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);

		curl_close($ch);

		

		return $response;																																			

	}

}



$api = new api;

?>