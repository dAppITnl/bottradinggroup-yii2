<?php

	include('./php-merchant-sdk/src/CQMerchantClient.class.php');

	$key = 'c7214d13cca6';
	$secret= 'f3DC-s6av-SHk@-WCXt-M@W$-f99@';

	$basicAuthHash = hash('sha256', $key . ':' . $secret);

	echo "\n\n".$basicAuthHash."\n\n";

	/*$header = implode("\r\n", [
		'accept: *'.'/'.'*',
		'Accept-Encoding: gzip, deflate, br',
		'Connection: keep-alive',
		'Transfer-Encoding: chunked',
		'Access-Control-Allow-Origin: *',
		'Server: nginx',
		'Content-type: application/x-www-form-urlencoded'
	]);
	echo "\nheader=".$header."\n";
	$opt = stream_context_create([
		'http' => [
			'method '=> 'GET',
			//'proxy' => 'tcp://127.0.0.1:8080', 'request_fulluri' => true
			'header' => $header
		]
	]);
	$serverTime = file_get_contents('https://www.coinqvest.com/api/v1/time', false, $opt);
	*/

	/*
	$handle = fopen("https://www.coinqvest.com/api/v1/time", "r");
	$serverTime = stream_get_contents($handle);
	fclose($handle);
*/

      $curl = curl_init();
      //$errFile = fopen('curlerror1.log', 'w');
      curl_setopt_array($curl, [
        CURLOPT_URL            => 'https://www.coinqvest.com/api/v1/time',
        //CURLOPT_HTTPHEADER     => ['Authorization: Bot '.$discordBotToken],
        CURLOPT_RETURNTRANSFER => 1,
   //     CURLOPT_FOLLOWLOCATION => 1,
   //     CURLOPT_VERBOSE        => 1,
   //     CURLOPT_SSL_VERIFYPEER => 0,
   //     CURLOPT_STDERR         => $errFile,
      ]);
      $result = json_decode(curl_exec($curl), true);
    //  fclose($errFile);
      curl_close($curl);
			$serverTime = $result['time'];
	echo "\nservertime=".$serverTime."\n";

	$client = new CQMerchantClient($key, $secret, 'coincvest.log');

	echo "\nclient..".date('H:i:s')."\n";

	$checkoutId = '54bbbd7f59bf';

	$response = $client->get('/auth-test');
	//$response = $client->get('/checkout', array('id' => $checkoutId));

	echo "\n\n".date('H:i:s')."\n".print_r($response,true)."\n\n";
