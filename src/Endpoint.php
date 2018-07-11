<?php

namespace luya\headless;

use ReflectionClass;
use luya\headless\base\BaseModel;
use luya\headless\base\EndpointInterface;
use luya\headless\endpoint\GetEndpointRequest;
use luya\headless\endpoint\PostEndpointRequest;
use luya\headless\endpoint\PutEndpointRequest;
use luya\headless\endpoint\DeleteEndpointRequest;
use luya\headless\base\BaseIterator;

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

    /**
     * Create an iterator of models for the current endpoint.
     * 
     * @param array $data
     * @param string $keyColumn
     * @return \luya\headless\base\BaseIterator
     */
    public static function iterator(array $data, $keyColumn = null)
    {
        return BaseIterator::create(get_called_class(), $data, $keyColumn);   
    }
}