<?php

namespace luya\headless;

/**
 * Base Request is used to make the Request to the API.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
abstract class BaseRequest
{
    /**
     * @var \luya\headless\Client
     */
    protected $client;
    
    /**
     * @var string The endpoint to request.
     */
    protected $endpoint;
    
    /**
     * Get request
     *
     * @param array $data
     * @return \luya\headless\BaseRequest
     */
    abstract public function get(array $data = []);
    
    /**
     * Get request
     *
     * @param array $data
     * @return \luya\headless\BaseRequest
     */
    abstract public function post(array $data = []);
    
    /**
     * Get request
     *
     * @param array $data
     * @return \luya\headless\BaseRequest
     */
    abstract public function put(array $data = []);
    
    /**
     * Get request
     *
     * @param array $data
     * @return \luya\headless\BaseRequest
     */
    abstract public function delete(array $data = []);
    
    /**
     * Whether current request is sucessfull or not.
     * 
     * @return boolean
     */
    abstract public function isSuccess();
    
    /**
     * Returns the RAW response content from the API.
     * 
     * @return string
     */
    abstract public function getResponseRawContent();
    
    /**
     * Returns the status code of the current parsed response.
     * 
     * @return integer
     */
    abstract public function getResponseStatusCode();
    
    /**
     * 
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * Setter method for endpoint.
     * 
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }
    
    /**
     * Returns the full qualified request url from client serverUrl and endpoint.
     * @return string
     */
    public function getRequestUrl()
    {
        return rtrim($this->client->serverUrl, '/') . '/' . ltrim($this->endpoint, '/');
    }
    
    /**
     * Parse and return the RAW content from {{getResponseRawContent()}} into an array structure.
     * 
     * @return array
     */
    public function getParsedResponse()
    {
        return json_decode($this->getResponseRawContent(), true);
    }
}