<?php

namespace luya\headless\base;

use luya\headless\GetEndpointRequest;
use luya\headless\PostEndpointRequest;
use luya\headless\PutEndpointRequest;
use luya\headless\DeleteEndpointRequest;

/**
 * Abstract Endpoint provides access to one endpoints based on the same endpoint node.
 * 
 * The Abstract Endpoint implementation requires to define the base endpoint trough {{getEndpointName()}}. From
 * this endpoint node you can also create multiple accessable endpoints. 
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
abstract class AbstractEndpoint extends BaseModel
{
    abstract public function getEndpointName();
    
    /**
     * Represents the CRUD find request.
     * 
     * @return \luya\headless\GetEndpointRequest
     */
    public static function find()
    {
        return static::get();
    }
    
    /**
     * Represents the CRUD insert request.
     * 
     * @param array $values
     * @return \luya\headless\PostEndpointRequest
     */
    public static function insert(array $values)
    {
        return static::post()->setArgs($values);
    }
    
    /**
     * Represents the CRUD update request.
     * 
     * @param integer $id
     * @param array $values
     * @return \luya\headless\base\AbstractEndpointRequest
     */
    public static function update($id, array $values)
    {
        return static::put()->setTokens(['{id}' => $id])->setEndpoint('{endpointName}/{id}');
    }
    
    /**
     * Represents the CRUD view request.
     * 
     * @param integer $id
     * @return \luya\headless\base\AbstractEndpointRequest
     */
    public static function view($id)
    {
        return static::get()->setTokens(['{id}' => $id])->setEndpoint('{endpointName}/{id}');
    }
    
    /**
     * Represents the CRUD remove/delete request.
     * 
     * @param integer $id
     * @return \luya\headless\base\AbstractEndpointRequest
     */
    public static function remove($id)
    {
        return static::delete()->setTokens(['{id}' => $id])->setEndpoint('{endpointName}/{id}');
    }
    
    /**
     * 
     * @return \luya\headless\GetEndpointRequest
     */
    public static function get()
    {
        return new GetEndpointRequest(new static);
    }
    
    /**
     * 
     * @return \luya\headless\PostEndpointRequest
     */
    public static function post()
    {
        return (new PostEndpointRequest(new static));
    }

    /**
     * 
     * @return \luya\headless\PutEndpointRequest
     */
    public static function put()
    {
        return (new PutEndpointRequest(new static));
    }
    
    /**
     * 
     * @return \luya\headless\DeleteEndpointRequest
     */
    public static function delete()
    {
        return (new DeleteEndpointRequest(new static));
    }
}