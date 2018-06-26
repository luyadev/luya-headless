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
    public function get(array $data = [])
    {
        return $this;   
    }
    
    /**
     * @inheritdoc
     */
    public function post(array $data = [])
    {
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function put(array $data = [])
    {
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function delete(array $data = [])
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
    
    /**
     * @inheritdoc
     */
    public function getResponseStatusCode()
    {
        return $this->success ? 200 : 500;
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