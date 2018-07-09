<?php

namespace luya\headless\endpoint;

use luya\headless\base\BaseIterator;
use luya\headless\Client;

class ActiveEndpointRequest extends AbstractEndpointRequest
{
    /**
     * Generate the response object.
     * 
     * @param Client $client
     * @return EndpointResponse
     */
    public function response(Client $client)
    {
        $request = $this->generateRequest($client);
        $request->get($this->getArgs() ?: []);
        
        return (new EndpointResponse($request, $this->endpointObject));
    }
    
    /**
     * Iterates over the current response content and assignes every item to the active endpoint model.
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
    
    /**
     * Takes the current response content into the active endpoint model.
     * 
     * @param Client $client
     * @return boolean|\luya\headless\ActiveEndpoint
     */
    public function one(Client $client)
    {
        $response = $this->response($client);
        
        if ($response->isError()) {
            return false;
        }
        
        $className = get_class($this->endpointObject);
        $model = new $className($response->getContent());
        $model->isNewRecord = false;
        return $model;
    }
}