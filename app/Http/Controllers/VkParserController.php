<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use VK\Client\VKApiClient;

class VkParserController extends Controller
{
    public function getVkPosts($count, $startPosition)
    {
        $wall_id = "-212254206";
        $group_id = preg_replace("/-/i", "", $wall_id); // Удаляем минус у ID групп, понадобится для ссылки.
        $count = "1";
        $token = config('vk.VK_SERVICE');
        $api_url = file_get_contents("https://api.vk.com/api.php?oauth=1&method=wall.get&owner_id={$wall_id}&count={$count}&v=5.131&access_token={$token}");
        $response = json_decode($api_url, true);
        //return $response;
        $arrPost = [];
        foreach($response['response']['items'] as $post)
             if(isset($post['text'])){
                $arrPost[]=[
                    'post_id'=>$post['id'],
                    'post_photo'=>$post['copy_history'][0]['attachments'][0]['photo']['sizes'][4]['url'],
                    'post_text'=>$post['copy_history'][0]['text'],
                    'post_date'=>$post['date'],
                    'post_link'=>'<a href="https://vk.com/wall-'.$group_id.'_'.$post['id'].'" target="_blank">https://vk.com/wall-'.$group_id.'_'.$post['id'].'</a>',
                ];
             }
                
            
        return view('blog.blogVk', ['data'=>$arrPost]);
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
