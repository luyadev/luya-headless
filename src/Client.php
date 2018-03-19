<?php

namespace luya\headless;

use luya\headless\collectors\CurlRequest;

class Client
{
    public $accessToken;
    
    public $serverUrl;
    
    public function __construct($accessToken, $serverUrl)
    {
        $this->accessToken = $accessToken;
        $this->serverUrl = $serverUrl;
    }
    
    private $_request;
    
    /**
     * @return \luya\headless\BaseRequest
     */
    public function getRequest()
    {
        if ($this->_request === null) {
            $this->_request = new CurlRequest($this);
        }

        return $this->_request;
    }
    
    public function setRequest(BaseRequest $request)
    {
        $this->_request = $request;
    }
}