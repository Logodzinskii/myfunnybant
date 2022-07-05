<?php

class VkParser extends VkSettings
{
    public function getVkPosts($count, $startPosition)
    {
        $url = 'https://api.vk.com/method/wall.get?owner_id=' . $this->getUserId() . '&access_token=' . $this->getToken(). '&domain=myfunnybant.ru&offset=' . $startPosition. '&count=' . $count . '&filter=owner&v=5.131';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($curl);
        return $res;
    }

    public function saveImage($url, $name)
    {

        $ch = curl_init($url);
        $fp = fopen( __DIR__ . '/image/'. $name , 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

    }
}