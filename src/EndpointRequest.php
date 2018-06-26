<?php

namespace luya\headless;

use Exception;
use luya\headless\base\AbstractEndpoint;

/**
 * EndpointRequest represents a request to a class with a response object in response().
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class EndpointRequest
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
    
    /**
     * Ensure whether the required args are provided or not.
     * @throws Exception
     */
    protected function ensureRequiredArguments()
    {
        $required = $this->endpoint->requiredArgs();
        
        foreach ($required as $key) {
            if (!array_key_exists($key, $this->_args)) {
                throw new Exception("Missing required arguments detected.");
            }
        }
    }
    
    private $_args = [];
    
    /**
     * Setter method for arguments: paremters
     * 
     * @param array $args
     * @return EndpointRequest
     */
    public function args(array $args)
    {
        $this->_args = array_merge($this->_args, $args);
        return $this;
    }
    
    /**
     * 
     * @param integer $id
     * @return \luya\headless\EndpointRequest
     */
    public function page($id)
    {
        return $this->args(['page' => $id]);
    }

    /**
     * 
     * @param integer $rows
     * @return \luya\headless\EndpointRequest
     */
    public function perPage($rows)
    {
        return $this->args(['per-page' => $rows]);
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
        
        return (new EndpointResponse($request));
    }
}