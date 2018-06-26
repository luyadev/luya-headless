<?php

namespace luya\headless;

use luya\headless\base\AbstractEndpointRequest;

/**
 * EndpointRequest represents a request to a class with a response object in response().
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class FindEndpointRequest extends AbstractEndpointRequest
{
    /**
     *
     * @param Client $client
     * @return array|mixed
     */
    public function response(Client $client)
    {
        $request = $client->getRequest();
        $request->setEndpoint($this->endpoint->getEndpointName());
        $request->get($this->getArgs() ?: []);
        
        return (new EndpointResponse($request));
    }
}