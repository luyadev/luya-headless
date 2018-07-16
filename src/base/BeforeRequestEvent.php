<?php

namespace luya\headless\base;

class BeforeRequestEvent
{
    public $url;
    public $data;
    
    public function __construct($url, array $data)
    {
        $this->url = $url;
        $this->data = $data;
    }
}