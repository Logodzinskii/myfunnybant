<?php

class YaDisk
{
    protected $token, $path;

    /**
     * @param string $YandexDiscToken
     */
    public function setToken(string $YandexDiscToken)
    {
        $this->token = $YandexDiscToken;
    }
    /**
     * @return mixed
     */
    public function getPath()
    {
        $this->path = file_get_contents('1.txt');
        return $this->path;
    }

    public function createPath($path)
    {

        $src = '/Ozon';

        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/?path=' . urlencode($src.'/'.$path));
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        if($res === FALSE) {

            return curl_error($ch);

        }else{
            file_put_contents('1.txt', $path);
            $this->path = $path;
            return 'Директория успешно создана';

        }

    }

    public function saveFile($name)
    {
        $token = $this->token;

// Путь и имя файла на нашем сервере.
        $file = $_SERVER['DOCUMENT_ROOT'].'/botapi/YaDisk/yadisk/upload/'.$name;

// Папка на Яндекс Диске (уже должна быть создана).
        $src = '/Ozon';

        $path = $src.'/'.self::getPath();

// Запрашиваем URL для загрузки.
        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/upload?path=' . urlencode($path . basename($file)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res, true);
        if (empty($res['error'])) {
            // Если ошибки нет, то отправляем файл на полученный URL.
            $fp = fopen($file, 'r');

            $ch = curl_init($res['href']);
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_UPLOAD, true);
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));
            curl_setopt($ch, CURLOPT_INFILE, $fp);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

             if ($http_code == 201) {
                return 'Файл успешно загружен.';
            }else{
                return $http_code;
            }
        }
    }
}
