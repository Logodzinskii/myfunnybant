<?php

$url = 'http://localhost/Module/client.myfunnybant/VK/postcreater.php';
$headers = 'Content-Type: application/json';
$curl = curl_init($url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, $headers);
$res = json_decode(curl_exec($curl), true);
curl_close($curl);
print_r($res[0]['image'][0]);
