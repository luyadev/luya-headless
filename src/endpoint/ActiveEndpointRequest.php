<?php

namespace luya\headless\endpoint;

use luya\headless\base\BaseIterator;
use luya\headless\Client;
use luya\headless\base\AbstractRequestClient;
use luya\headless\base\AbstractEndpointRequest;

class ActiveEndpointRequest extends AbstractEndpointRequest
{
    /**
     * Response trough get request.
     *
     * {@inheritDoc}
     * @see \luya\headless\base\AbstractEndpointRequest::createResponse()
     */
    public function createResponse(AbstractRequestClient $request)
    {
        return (new EndpointResponse($request->get($this->getArgs() ?: []), $this->endpointObject));
    }
    
    /**
     * Iterates over the current response content and assignes every item to the active endpoint model.
     * 
     * ```php
     * $models = APi::find()->all($client);
     * ```
     * 
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
     * Takes the first row from an an array into an active endpoint model.
     * 
     * This is comonoly used when retrieving data from find but return only the first record.
     * 
     * ```php
     * $model = Api::find()->setPerPage(1)->first($client);
     * ```
     * 
     * @param Client $client
     * @since 1.0.0
     * @return static
     */
    public function first(Client $client)
    {
        $response = $this->response($client);
        
        if ($response->isError() && !$client->debug) {
            $models = [];
        } else {
            $models = $response->getContent();
        }

        $content = current($models);
        $className = get_class($this->endpointObject);
        $model = new $className($content);
        $model->isNewRecord = false;
        return $model;
    }
    
    /**
     * Takes the current response content into the active endpoint model.
     * 
     * ```php
     * $model = Api::view($id)->setExpand(['images'])->one($client);
     * ```
     * 
     * @param Client $client
     * @return static
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