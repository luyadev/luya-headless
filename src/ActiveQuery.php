<?php

namespace luya\headless;

class ActiveQuery
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