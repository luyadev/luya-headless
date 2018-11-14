<?php
namespace luya\headless\base;

/**
 * Endpoint Interface.
 *
 * Every endpoint defintion must implement this interface.
 *
 * @since 1.0.0
 * @author Basil Suter <basil@nadar.io>
 */
interface EndpointInterface
{
    /**
     * Returns the name of the endpoint.
     *
     * @return string
     */
    public function getEndpointName();
    
    /**
     * Create a get request.
     */
    public static function get();
    
    /**
     * Create a post request.
     */
    public static function post();
    
    /**
     * Create a put request.
     */
    public static function put();
    
    /**
     * Create a delete request.
     */
    public static function delete();
}
