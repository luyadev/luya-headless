<?php

namespace luya\headless;

use luya\headless\base\AbstractEndpointRequest;

/**
 * EndpointRequest represents a request to a class with a response object in response().
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class PutEndpointRequest extends AbstractEndpointRequest
{
    /**
     *
     * @param Client $client
     * @return array|mixed
     */
    public function response(Client $client)
    {
        $request = $this->generateRequest($client);
        $request->put($this->getArgs() ?: []);
        
        return (new EndpointResponse($request, $this->endpointObject));
    }
}