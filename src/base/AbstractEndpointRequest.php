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
    /**
     * Create a response.
     * 
     * @param Client $client
     * @return \luya\headless\EndpointResponse
     */
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
    
    private $_tokens;
    
    /**
     * A list of tokens which will be parsed while generating the endpointName. Example
     *
     * ```php
     * 'tokens' => [
     *     '{id}' => 1,
     *     '{name}' => 'foobar',
     * ];
     * ```
     *
     * You can now use the tokens in curly braced in the endpoint string like:
     *
     * ```php
     * setEndpoint('admin/api-user-login/{id}')
     * ```
     * 
     * There is also a predifend token {{endpointName}} which will represent the endpoint name
     * from the {{luya\headless\base\AbstractEndpoint::getEndpointName()}}.
     *
     * which would replace {id} with 1 from the tokens list.
     *
     * @param array $tokens
     */
    public function setTokens(array $tokens)
    {
        $this->_tokens = $tokens;
        
        return $this;
    }
    
    private $_endpoint;
    
    /**
     * 
     * @param string $name The endpoint name, in order to extend the current endpointName from the endpoint defintion you can use {endpointName}/foobar.
     * 
     * @return \luya\headless\base\AbstractEndpointRequest
     */
    public function setEndpoint($name)
    {
        $this->_endpoint = $name;
        
        return $this;
    }
    
    /**
     * 
     * @return mixed
     */
    public function getEndpoint()
    {
        $tokens = $this->_tokens;
        $tokens['{endpointName}'] = $this->endpoint->getEndpointName();
        return $this->parseTokens($this->_endpoint ?: $this->endpoint->getEndpointName(), $tokens);
    }

    /**
     * 
     * @param string $string
     * @param array $tokens
     * @return mixed
     */
    protected function parseTokens($string, array $tokens)
    {
        return str_replace(array_keys($tokens), array_values($tokens), $string);
    }
    
    /**
     * 
     * @param Client $client
     * @return \luya\headless\base\AbstractRequest
     */
    protected function generateRequest(Client $client)
    {
        $request = $client->getRequest();
        $request->setEndpoint($this->getEndpoint());
        
        return $request;
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