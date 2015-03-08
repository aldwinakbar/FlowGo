<?php

function lib(){
//define variables
//$url = "https://api.bigdataindonesia.com/poi/";
$lat = random_lat();
$lng = random_lng();

$url = "https://api.bigdataindonesia.com/poi/nearby/json?lat=".$lat."&lng=".$lng."&rad=0.5";
$method = "nearby"; // You can change with another method (textsearch/area/specific/nearby)
$output = "json"; // You can change with another output (json / xml)

//define paramaters

//$paramters ="lat=.'$lat'.&lng=.'$lng'.&rad=0.0";
//$paramters = "lat=-6.8731&lng=107.607&rad=0.5 ";

//$send_params = http_build_query($paramters);

//combine url request
//$send_url = $url."".$method."/".$output."?".$send_params;

// set config key
$config = array(
"key" => "3979e7e87532f0fbd7bf769a6179b021",
);

// sending API Request
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $config);
curl_setopt ($ch, CURLOPT_TIMEOUT, 120);
ob_start();
curl_exec ($ch);
curl_close ($ch);
$result = ob_get_contents();
ob_end_clean();

return $result;

}

function random_lat(){
	$base = -6;
	$random = rand(20100, 20700) / 100000;
	$res = $base - $random;
	return $res;

}

function random_lng(){
	$base = 106;
	$random = rand(800, 825) / 1000;
	$res = $base + $random;
	return $res;

}
?> 
