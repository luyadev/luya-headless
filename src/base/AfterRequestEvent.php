<?php

namespace luya\headless\base;

/**
 * After Request Event.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class AfterRequestEvent
{
    public $url;
    public $data;
    public $statusCode;
    public $content;
    public $type;

    /**
     * @var AbstractRequestClient
     */
    public $requestClient;

    public function __construct($url, array $data, $statusCode, $content, $type, AbstractRequestClient $requestClient)
    {
        $this->url = $url;
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->content = $content;
        $this->type = $type;
        $this->requestClient = $requestClient;
    }
}
