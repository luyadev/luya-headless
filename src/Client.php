<?php

namespace luya\headless;

use luya\headless\collectors\CurlRequest;
use luya\headless\base\AbstractRequest;

/**
 * Client provides Auth and Connection informations.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Client
{
    public $accessToken;
    
    public $serverUrl;
    
    public $language;
    
    public function __construct($accessToken, $serverUrl, $language = null)
    {
        $this->accessToken = $accessToken;
        $this->serverUrl = $serverUrl;
        $this->language = $language;
    }
    
    private $_request;
    
    /**
     * @return \luya\headless\base\AbstractRequest
     */
    public function getRequest()
    {
        if ($this->_request === null) {
            $this->_request = new CurlRequest($this);
        }

        return $this->_request;
    }
    
    public function setRequest(AbstractRequest $request)
    {
        $this->_request = $request;
    }
}