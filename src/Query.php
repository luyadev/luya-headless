<?php

namespace luya\headless;

/**
 * Query represents a Query Builder for Handling the response Data.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Query
{
    protected $endpoint;
    
    public function __construct(BaseEndpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }
    
    public function all(Client $client)
    {
        $request = $client->getRequest();
        $request->setEndpoint($this->endpoint->getEndpointName());
        $request->get();
        
        return $request->getParsedResponse();
    }
}