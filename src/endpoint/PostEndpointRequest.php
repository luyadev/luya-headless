<?php

namespace luya\headless\endpoint;


use luya\headless\Client;

/**
 * EndpointRequest represents a request to a class with a response object in response().
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class PostEndpointRequest extends AbstractEndpointRequest
{
    /**
     *
     * @param Client $client
     * @return array|mixed
     */
    public function response(Client $client)
    {
        $request = $this->generateRequest($client);
        $request->post($this->getArgs() ?: []);
        
        return (new EndpointResponse($request, $this->endpointObject));
    }
}