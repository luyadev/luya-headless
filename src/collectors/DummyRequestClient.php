<?php

namespace luya\headless\collectors;

use luya\headless\base\AbstractRequestClient;

/**
 * Dummy Request Client for UnitTests
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class DummyRequestClient extends AbstractRequestClient
{
    public $response;
    
    public $success = true;
    
    public $responseHeaderMapping = [];
    
    /**
     * @inheritdoc
     */
    public function internalGet()
    {
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function internalPost(array $data = [])
    {
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function internalPut(array $data = [])
    {
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function internalDelete(array $data = [])
    {
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function isSuccess()
    {
        return $this->success;
    }
    
    public $statusCode;
    
    /**
     * @inheritdoc
     */
    public function getResponseStatusCode()
    {
        if ($this->success) {
            return $this->statusCode ?: 200;
        }
        
        return $this->statusCode ?: 500;
    }
    
    /**
     * @inheritdoc
     */
    public function getResponseRawContent()
    {
        return $this->response;
    }
    
    /**
     * @inheritdoc
     */
    public function getResponseHeader($key)
    {
        return isset($this->responseHeaderMapping[$key]) ? $this->responseHeaderMapping[$key] : $key;
    }
    
    /**
     * @inheritdoc
     */
    public function hasConnectionError()
    {
        return false;
    }
    
    /**
     * @inheritdoc
     */
    public function getConnectionErrorMessage()
    {
        return null;
    }
}
