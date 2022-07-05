<?php

class VkSettings
{
    protected $token, $url, $expires_in, $user_id;

    public function __construct(){

        $this->url = 'https://oauth.vk.com/blank.html#access_token=771199b8849b08e5e5c2bbc81a30e397b3dfda17a8487a124adb9fba1c98553cf2302f9bdcc6d21c72cb6&expires_in=0&user_id=534750&email=chela@e1.ru';
        $this->token = '771199b8849b08e5e5c2bbc81a30e397b3dfda17a8487a124adb9fba1c98553cf2302f9bdcc6d21c72cb6';
        $this->expires_in = '86400';
        $this->user_id = '18851356'; //18851356   534750
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getExpiresIn(): string
    {
        return $this->expires_in;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

}

