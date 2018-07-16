<?php

namespace luya\headless\collectors;

use luya\headless\base\AbstractRequest;

/**
 * Dummy Request for UnitTests
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class DummyRequest extends AbstractRequest
{
    public $response;
    
    public $success = true;
    
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
        return $this->success ? $this->statusCode ?: 200 : $this->statusCode ?: 500;
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
        return $key;
    }
}