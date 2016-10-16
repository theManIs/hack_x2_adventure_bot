<?php
/* 
$personal_access_token = 'MDMzNjAwNWItMjE0Yi00OTQ3LWFkODAtODc3NmNjYWM4YWE4ZDVhNDM0MDEtYjZm';
$curl_com = 'curl https://api.ciscospark.com/v1/messages -X POST -H "Authorization:Bearer MDMzNjAwNWItMjE0Yi00OTQ3LWFkODAtODc3NmNjYWM4YWE4ZDVhNDM0MDEtYjZm" --data "toPersonId=722bb271-d7ca-4bce-a9e3-471e4412fa77" --data "text=Hi%20Sparky"';


$str_result = shell_exec($curl_com);
$json_result = json_decode($str_result, true);

var_dump($json_result);



 */
 
$output = file_get_contents('php://input');

file_put_contents('spark_log.txt', $output, FILE_APPEND);



// $curl = curl_init('https://api.ciscospark.com/v1/rooms');

// curl_setopt_array($curl, array(
	// CURLOPT_POST => true,
	// CURLOPT_POSTFIELDS	=> array(
		// "title" => "Project Unicorn - Sprint 0",
		// "teamId" => "Y2lzY29zcGFyazovL3VzL1JPT00vNjRlNDVhZTAtYzQ2Yi0xMWU1LTlkZjktMGQ0MWUzNDIxOTcz"
	// ),
// ));

// $transfer = curl_exec($curl);
// $info = curl_getinfo($curl);
// var_dump($info);
// curl_close($curl);