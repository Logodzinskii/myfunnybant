<?php

class VkSettings
{
    protected $token, $url, $expires_in, $user_id;

    public function __construct(){

        $this->url = '';
        $this->token = '';
        $this->expires_in = '';
        $this->user_id = ''; 
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

