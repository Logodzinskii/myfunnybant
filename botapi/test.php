<?php

$str = '{"latitude":0,"longitude":0}';
$array = get_object_vars(json_decode($str));
var_dump($array['latitude']);
