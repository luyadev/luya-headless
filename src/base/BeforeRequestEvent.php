<?php

namespace luya\headless\base;

class BeforeRequestEvent
{
    public $url;
    public $data;
    public $type;

    public function __construct($url, array $data, $type)
    {
        $this->url = $url;
        $this->data = $data;
        $this->type = $type;
    }
}
