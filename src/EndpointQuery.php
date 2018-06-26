<?php

namespace luya\headless;

use Exception;
use luya\headless\base\AbstractEndpoint;

/**
 * Query represents a Query Builder for Handling the response Data.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class EndpointQuery
{
    /**
     * @var AbstractEndpoint
     */
    protected $endpoint;
    
    /**
     * 
     * @param AbstractEndpoint $endpoint
     */
    public function __construct(AbstractEndpoint $endpoint)
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
    public function response(Client $client)
    {
        $request = $client->getRequest();
        $request->setEndpoint($this->endpoint->getEndpointName());
        $request->get($this->_args ?: []);
        
        var_dump($request);
        exit;
        
        return $request->getParsedResponse();
    }
}