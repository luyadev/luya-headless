<?php

namespace luya\headless\base;

/**
 * Before Event Request
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class BeforeRequestEvent
{
    public $url;
    public $data;
    public $type;

    /**
     * Constructor
     *
     * @param string $url
     * @param array $data
     * @param string $type
     */
    public function __construct($url, array $data, $type)
    {
        $this->url = $url;
        $this->data = $data;
        $this->type = $type;
    }
}
