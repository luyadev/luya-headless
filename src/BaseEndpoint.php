<?php

namespace luya\headless;

/**
 * Base Endpoint represents the implementation of given admin api endpoint defintion.
 * 
 * @since 1.0.0
 */
abstract class BaseEndpoint
{
    abstract public function getEndpointName();
    
    public static function find()
    {
        return new ActiveQuery(new static);
    }
}