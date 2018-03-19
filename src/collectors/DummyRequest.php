<?php

namespace luya\headless\collectors;

use luya\headless\BaseRequest;

class DummyRequest extends BaseRequest
{
    public $response;
    
    public $success = true;
    
    /**
     *
     * @param string $endpoint
     * @param array $data
     * @return \Curl\Curl
     */
    public function get(array $data = [])
    {
        
    }
    
    public function post(array $data = [])
    {
        
    }
    
    public function put(array $data = [])
    {
        
    }
    
    public function patch(array $data = [])
    {
        
    }
    
    public function delete(array $data = [])
    {
        
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