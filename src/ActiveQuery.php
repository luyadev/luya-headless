<?php

namespace luya\headless;

use luya\headless\endpoints\BaseEndpoint;

class ActiveQuery
{
    protected $endpoint;
    
    public function __construct(BaseEndpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }
    
    public function all(Client $client)
    {
        $request = new Request($client);
        
        $curl = $request->get($this->endpoint->endpointName());

        return $request->parseResponse($curl);
    }
}