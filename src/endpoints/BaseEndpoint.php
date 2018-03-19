<?php

namespace luya\headless\endpoints;

use luya\headless\ActiveQuery;

abstract class BaseEndpoint
{
    abstract public function endpointName();
    
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }
}