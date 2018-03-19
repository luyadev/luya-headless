<?php

namespace luya\headless;

abstract class BaseEndpoint
{
    abstract public function getEndpointName();
    
    public static function find()
    {
        return new ActiveQuery(new static);
    }
}