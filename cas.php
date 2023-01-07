<?php
echo "\n\n";

/*	$symbol='eth';

	$url = 'https://api.binance.com/api/v3/ticker/price?symbol='.strtoupper($symbol).'EUR';
	$pricedata = file_get_contents($url);
	echo "\npricedata=".$pricedata;
	if (!empty($pricedata)) {
		$pricejson = json_decode($pricedata, true);
		echo "\npricejson=".print_r($pricejson,true);
	}
*/

/*	$i=0;
	for($x=0; $x<5; $x++) {
		if ($i++ > 0) echo " i=".$i;
		echo " x=".$x."\n";
	}
*/

	$hdr = [];
	$hdr[] = 'data 1';
	$hdr[] = 'data 2';
	$hdr[] = 'data 3';
	echo 'hdr='.print_r($hdr, true);

	$hdr = [];
	array_push($hdr, ...['data 1']);
	array_push($hdr, ...['data 2']);
  array_push($hdr, ...['data 3', 'data 4']);
	echo 'hdr='.print_r($hdr, true);

echo "\n\n";
