<?php

class VkCreateJsonPost
{
    function exequteArrayPost($posts)
    {
        $telegramMessage = [
            'capture'=>$posts['text'],
        ];
        $arr=[];
        foreach ($posts['attachments'] as $post) {
            if ($post['type'] === 'photo') {

                $findHeight = 'z';
                $height = array_filter($post['photo']['sizes'], function ($value) use ($findHeight) {
                    return ($value["type"] === $findHeight);
                });
                //print_r($height) . ' ' . PHP_EOL;

                foreach ($height as $imgUrl)
                {
                    //скачаем и сохраним фото
                    $name = substr(basename($imgUrl['url']), 0 , strpos(basename($imgUrl['url']), '?'));
                    //
                    $vkPars = new VkParser();
                    $vkPars->saveImage($imgUrl['url'], $name);
                    $arr[] = $name;
                }
                $telegramMessage['image']=$arr;
                //print_r($arr);
            }

        }

        return $telegramMessage;
    }
}