<?php

namespace luya\headless\base;

use luya\headless\GetEndpointRequest;
use luya\headless\PostEndpointRequest;

/**
 * Base Endpoint represents the implementation of given admin api endpoint defintion.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
abstract class AbstractEndpoint
{
    abstract public function getEndpointName();
    
    public function requiredArgs()
    {
        return [];
    }
    
    public static function find()
    {
        return new GetEndpointRequest(new static);
    }
    
    public static function view($id)
    {
        return (new GetEndpointRequest(new static))->setTokens(['{id}' => $id])->setEndpoint('{endpointName}/{id}');
    }
    
    public static function post(array $values)
    {
        return (new PostEndpointRequest(new static));   
    }

    public static function put($id)
    {
        
    }
    
    public static function delete($id)
    {
        
    }
}