<?php

namespace luya\headless\base;

class AfterRequestEvent
{
    public $url;
    public $data;
    public $statusCode;
    public $content;
    public $type;

    public function __construct($url, array $data, $statusCode, $content, $type)
    {
        $this->url = $url;
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->content = $content;
        $this->type = $type;
    }
}
