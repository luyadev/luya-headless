<?php

namespace luya\headless\base;



use luya\headless\FindEndpointRequest;
use luya\headless\ViewEndpointRequest;

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
        return new FindEndpointRequest(new static);
    }
    
    public static function view($id)
    {
        return (new ViewEndpointRequest(new static))->setId($id);
    }

    public static function update($id)
    {
        
    }
    
    public static function delete($id)
    {
        
    }
    
    public static function put($id)
    {
        
    }
}