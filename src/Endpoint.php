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
 * Endpoint Object contains methods to make requests to an API Endpoint.
 *
 * When extending for this Endpoint class you may override {{getEndpointName()}} as long as you are not following
 * the Camelcase notation where the class name is the same as the api endpoint.
 *
 * ```php
 * class MyEndpoint extends Endpoint
 * {
 *     public function getEndpointName()
 *     {
 *         retunr '{{%my-endpoint}}';
 *     }
 * }
 * ```
 *
 * Keep in mind the full qualified url will be concated from the {{Client::$serverUrl}} property. Assuming
 * $serverUrl is `https://luya.io` the request is made for the URL `https://luya.io/my-endpoint`.
 *
 * The Endpoint class provides basic request implementations for:
 *
 * + get: Create a get request to the given endpoint - `MyEndpoint::get()->response(Client)`
 * + post: Create a post request to the given endpoint - `MyEndpoint::post()->setArgs(['foo' => 'bar'])->response(Client)`
 * + put: Create a put/patch request to the given endpoint - `MyEndpoint::put()->setArgs(['foo' => 'bar'])->response(Client)`
 * + delete: Create a delete request for the given endpoint - `MyEndpoint::delete()->response(Client)`
 *
 * Every request creates an object of {{luya\headless\endpoint\EndpointResponse}}, there you can access further methods to
 * accces the response data or its status:
 *
 * + `getContent()`:
 * + `isSuccess()`:
 * + `isError()`:
 * + `getStatusCode()`:
 *
 * If the response contains pagination informations in the header you can access those informations with:
 *
 * + `getTotalCount()`
 * + `getPageCount()`
 * + `getCurrentPage()`
 * + `getPerPage()`
 * + `isLastPage()`
 * + `isFirstPage()`
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
        return '{{%'.strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', (new ReflectionClass($this))->getShortName())).'}}';
    }
    
    /**
     * Create a GET request.
     *
     * @return GetEndpointRequest
     */
    public static function get()
    {
        return (new GetEndpointRequest(new static));
    }
    
    /**
     * Create a POST request.
     *
     * @return PostEndpointRequest
     */
    public static function post()
    {
        return (new PostEndpointRequest(new static));
    }

    /**
     * Create a PUT request.
     *
     * @return PutEndpointRequest
     */
    public static function put()
    {
        return (new PutEndpointRequest(new static));
    }
    
    /**
     * Create a DELETE request.
     *
     * @return DeleteEndpointRequest
     */
    public static function delete()
    {
        return (new DeleteEndpointRequest(new static));
    }
}
