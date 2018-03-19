<?php

namespace luya\headless;

class Client
{
    protected $accessToken;
    protected $serverUrl;
    
    public function __construct($accessToken, $admin)
    {
        $this->accessToken = $accessToken;
        $this->serverUrl = $serverUrl;
    }
    
    public function request()
    {
        return new Request($this);
    }
}