<?php

namespace luya\headless;

use ReflectionClass;
use luya\headless\base\BaseModel;
use luya\headless\base\EndpointInterface;
use luya\headless\endpoint\GetEndpointRequest;
use luya\headless\endpoint\PostEndpointRequest;
use luya\headless\endpoint\PutEndpointRequest;
use luya\headless\endpoint\DeleteEndpointRequest;

/**
 * Abstract Endpoint provides access to one endpoints based on the same endpoint node.
 * 
 * The Abstract Endpoint implementation requires to define the base endpoint trough {{getEndpointName()}}. From
 * this endpoint node you can also create multiple accessable endpoints. 
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Endpoint extends BaseModel implements EndpointInterface
{
    /**
     * @inheritdoc
     */
    public function getEndpointName()
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', (new ReflectionClass($this))->getShortName()));
    }
    
    /**
     * Represents the CRUD index request. Listing of data.
     * 
     * @return GetEndpointRequest
     */
    public static function index()
    {
        return static::get();
    }
    
    /**
     * Represents the CRUD insert request.
     * 
     * @param array $values
     * @return PostEndpointRequest
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
     * @return PutEndpointRequest
     */
    public static function update($id, array $values)
    {
        return static::put()->setTokens(['{id}' => $id])->setArgs($values)->setEndpoint('{endpointName}/{id}');
    }
    
    /**
     * Represents the CRUD view request.
     * 
     * @param integer $id
     * @return GetEndpointRequest
     */
    public static function view($id)
    {
        return static::get()->setTokens(['{id}' => $id])->setEndpoint('{endpointName}/{id}');
    }
    
    /**
     * Represents the CRUD remove/delete request.
     * 
     * @param integer $id
     * @return DeleteEndpointRequest
     */
    public static function remove($id)
    {
        return static::delete()->setTokens(['{id}' => $id])->setEndpoint('{endpointName}/{id}');
    }
    
    /**
     * 
     * @return GetEndpointRequest
     */
    public static function get()
    {
        return (new GetEndpointRequest(new static));
    }
    
    /**
     * 
     * @return PostEndpointRequest
     */
    public static function post()
    {
        return (new PostEndpointRequest(new static));
    }

    /**
     * 
     * @return PutEndpointRequest
     */
    public static function put()
    {
        return (new PutEndpointRequest(new static));
    }
    
    /**
     * 
     * @return DeleteEndpointRequest
     */
    public static function delete()
    {
        return (new DeleteEndpointRequest(new static));
    }
}