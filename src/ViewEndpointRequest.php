<?php

namespace luya\headless;

use luya\headless\base\AbstractEndpointRequest;

/**
 * EndpointRequest represents a request to a class with a response object in response().
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ViewEndpointRequest extends AbstractEndpointRequest
{
    private $_id;
    
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }
    
    /**
     *
     * @param Client $client
     * @return array|mixed
     */
    public function response(Client $client)
    {
        $request = $client->getRequest();
        $request->setEndpoint($this->endpoint->getEndpointName() . '/' . $this->_id);
        $request->get($this->getArgs() ?: []);
        
        return (new EndpointResponse($request));
    }
}