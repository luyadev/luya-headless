<?php

namespace luya\headless\base;

use Exception;
use luya\headless\Client;

/**
 * EndpointRequest represents a request to a class with a response object in response().
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
abstract class AbstractEndpointRequest
{
    abstract public function response(Client $client);
    
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
     * @return self
     */
    public function setArgs(array $args)
    {
        $this->_args = array_merge($this->_args, $args);
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getArgs()
    {
        return $this->_args;
    }
    
    /**
     * 
     * @param array $extraFields
     */
    public function setExpand(array $extraFields)
    {
        return $this->setArgs(['expand' => implode(",", $extraFields)]);
    }
    
    /**
     *
     * @param integer $id
     * @return self
     */
    public function setPage($id)
    {
        return $this->setArgs(['page' => $id]);
    }
    
    /**
     *
     * @param integer $rows
     * @return self
     */
    public function setPerPage($rows)
    {
        return $this->setArgs(['per-page' => $rows]);
    }
}