<?php
$text = '{"latitude":57.489757,"longitude":60.222595}';
$geolocation = json_decode($text, true);
$longtitude = $geolocation['longitude'];
$latitude = $geolocation['latitude'];


