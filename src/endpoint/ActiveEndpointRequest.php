<?php

namespace luya\headless\endpoint;

use luya\headless\base\BaseIterator;
use luya\headless\Client;

class ActiveEndpointRequest extends AbstractEndpointRequest
{
    /**
     *
     * @param Client $client
     * @return array|mixed
     */
    public function response(Client $client)
    {
        $request = $this->generateRequest($client);
        $request->get($this->getArgs() ?: []);
        
        return (new EndpointResponse($request, $this->endpointObject));
    }
    
    /**
     * 
     * @param Client $client
     * @return \luya\headless\endpoint\ActiveEndpointResponse
     */
    public function all(Client $client)
    {
        $response = $this->response($client);
        
        if ($response->isError()) {
            $models = [];
        } else {
            $models = $response->getContent();
        }
        
        $models = BaseIterator::create(get_class($this->endpointObject), $models, $response->endpoint->getPrimaryKeys(), false);
        
        return new ActiveEndpointResponse($response, $models);
    }
}