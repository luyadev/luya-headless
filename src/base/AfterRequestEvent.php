<?php

namespace luya\headless\base;

class AfterRequestEvent
{
    public $url;
    public $data;
    public $statusCode;
    public $content;
    
    public function __construct($url, array $data, $statusCode, $content)
    {
        $this->url = $url;
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->content = $content;
    }
}
