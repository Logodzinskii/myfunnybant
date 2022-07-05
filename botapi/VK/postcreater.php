<?php

include_once __DIR__ . '/autoloader.php';

header("Content-type: application/json; charset=utf-8");

$vk = new VkSettings();
$vkPars = new VkParser();
$vkCreateJson = new VkCreateJsonPost();

$countPost = 1;
$startPosition = 0;
$posts = json_decode($vkPars->getVkPosts($countPost, $startPosition), true);

$jsonReturn = [];

for($i = 0; $i <= ($countPost - 1); $i++)
{
    if(array_key_exists('copy_history', $posts['response']['items'][$i]))
    {
        $parserPost = $posts['response']['items'][$i]['copy_history'][0];
        //print_r($parserPost);
        $jsonReturn[] = $vkCreateJson->exequteArrayPost($parserPost);
    }else{
        $parserPost = $posts['response']['items'][$i];
        //print_r($parserPost);
        $jsonReturn[] = $vkCreateJson->exequteArrayPost($parserPost);
    }

}

echo json_encode($jsonReturn);

/*if (!is_array($jsonReturn)){
    return json_encode($jsonReturn);
}else{
    return json_encode(['capture'=>'error']);
}*/
