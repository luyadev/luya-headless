<?php

namespace luya\headless;

use Exception;

/**
 * Query represents a Query Builder for Handling the response Data.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Query
{
    /**
     * @var BaseEndpoint
     */
    protected $endpoint;
    
    /**
     * 
     * @param BaseEndpoint $endpoint
     */
    public function __construct(BaseEndpoint $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->ensureRequiredArguments();
    }
    
    protected function ensureRequiredArguments()
    {
        $required = $this->endpoint->requiredArgs();
        
        foreach ($required as $key) {
            if (!array_key_exists($key, $this->_args)) {
                throw new Exception("Missing required arguments detected.");
            }
        }
    }
    
    private $_args;
    
    public function args(array $args)
    {
        $this->_args = $args;
        return $this;
    }
    
    /**
     *
     * @param Client $client
     * @return array|mixed
     */
    public function all(Client $client)
    {
        $request = $client->getRequest();
        $request->setEndpoint($this->endpoint->getEndpointName());
        $request->get($this->_args ?: []);
        
        return $request->getParsedResponse();
    }
}