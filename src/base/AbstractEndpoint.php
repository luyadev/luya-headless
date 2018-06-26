<?php

namespace luya\headless\base;

use luya\headless\EndpointRequest;

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
        return new EndpointRequest(new static);
    }
}