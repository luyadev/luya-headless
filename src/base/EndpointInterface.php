<?php
namespace luya\headless\base;

interface EndpointInterface
{
    public function getEndpointName();
    
    public static function get();
    
    public static function post();
    
    public static function put();
    
    public static function delete();
}

