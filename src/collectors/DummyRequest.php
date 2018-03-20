<?php

namespace luya\headless\collectors;

use luya\headless\BaseRequest;

/**
 * Dummy Request for UnitTests
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class DummyRequest extends BaseRequest
{
    public $response;
    
    public $success = true;
    
    /**
     * Get request
     *
     * @param array $data
     * @retunr BaseRequest
     */
    public function get(array $data = [])
    {
        return $this;   
    }
    
    /**
     * Get request
     *
     * @param array $data
     * @retunr BaseRequest
     */
    public function post(array $data = [])
    {
        return $this;
    }
    
    /**
     * Get request
     *
     * @param array $data
     * @retunr BaseRequest
     */
    public function put(array $data = [])
    {
        return $this;
    }
    
    /**
     * Get request
     *
     * @param array $data
     * @retunr BaseRequest
     */
    public function delete(array $data = [])
    {
        return $this;
    }
    
    public function isSuccess()
    {
        return $this->success;
    }
    
    public function getResponseContent()
    {
        return $this->response;
    }   
}