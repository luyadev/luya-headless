<?php

namespace luya\headless\endpoint;

use luya\headless\base\BaseIterator;
use luya\headless\Client;
use luya\headless\base\AbstractRequest;

class ActiveEndpointRequest extends AbstractEndpointRequest
{
    /**
     * Response trough get request.
     *
     * {@inheritDoc}
     * @see \luya\headless\endpoint\AbstractEndpointRequest::createResponse()
     */
    public function createResponse(AbstractRequest $request)
    {
        return (new EndpointResponse($request->get($this->getArgs() ?: []), $this->endpointObject));
    }
    
    /**
     * Iterates over the current response content and assignes every item to the active endpoint model.
     * @param Client $client
     * @return \luya\headless\endpoint\ActiveEndpointResponse
     */
    public function all(Client $client)
    {
        $response = $this->response($client);
        
        if ($response->isError() && !$client->debug) {
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
        
        if ($response->isError() && !$client->debug) {
            return false;
        }
        
        $className = get_class($this->endpointObject);
        $model = new $className($response->getContent());
        $model->isNewRecord = false;
        return $model;
    }
}