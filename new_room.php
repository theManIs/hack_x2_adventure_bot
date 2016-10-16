<?php

// $personal_access_token = 'MDMzNjAwNWItMjE0Yi00OTQ3LWFkODAtODc3NmNjYWM4YWE4ZDVhNDM0MDEtYjZm';
// $curl_com = 'curl https://api.ciscospark.com/v1/messages -X POST -H "Authorization:Bearer MDMzNjAwNWItMjE0Yi00OTQ3LWFkODAtODc3NmNjYWM4YWE4ZDVhNDM0MDEtYjZm" --data "toPersonId=722bb271-d7ca-4bce-a9e3-471e4412fa77" --data "text=Hi%20Sparky"';

if (!key_exists('room', $_GET)) {
	$curl_com = 'curl https://api.ciscospark.com/v1/rooms -X POST -H "Authorization:Bearer MDMzNjAwNWItMjE0Yi00OTQ3LWFkODAtODc3NmNjYWM4YWE4ZDVhNDM0MDEtYjZm"';
	$room_title = '--data "title=' . md5(rand()) . '"';
	$jas = json_decode(shell_exec("$curl_com $room_title"), true);
	
	header('Location: ?room=' . $jas['id']);
	// var_dump($jas);
} else {
	$curl_com = 'curl https://api.ciscospark.com/v1/messages -X GET -H "Authorization:Bearer MDMzNjAwNWItMjE0Yi00OTQ3LWFkODAtODc3NmNjYWM4YWE4ZDVhNDM0MDEtYjZm"';
	$room_title = '--data "roomId=' . $_GET['room'] . '"';
	echo "$curl_com $room_title";
	echo shell_exec("$curl_com $room_title");
}


// echo "$curl_com $room_title";
