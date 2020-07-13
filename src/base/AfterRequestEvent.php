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
     * @since 2.6.0
     */
    public $requestClient;

    /**
     * Constructor
     *
     * @param string $url
     * @param array $data
     * @param integer $statusCode
     * @param mixed $content
     * @param string $type
     * @param AbstractRequestClient $requestClient
     */
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
