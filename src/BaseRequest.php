<?php

namespace luya\headless;

/**
 * Base Request is used to make the Request to the API.
 *
 * @since 1.0.0
 */
abstract class BaseRequest
{
    /**
     * Get request
     *
     * @param array $data
     * @retunr BaseRequest
     */
    abstract public function get(array $data = []);
    
    /**
     * Get request
     *
     * @param array $data
     * @retunr BaseRequest
     */
    abstract public function post(array $data = []);
    
    /**
     * Get request
     *
     * @param array $data
     * @retunr BaseRequest
     */
    abstract public function put(array $data = []);
    
    /**
     * Get request
     *
     * @param array $data
     * @retunr BaseRequest
     */
    abstract public function delete(array $data = []);
    
    abstract public function isSuccess();
    
    abstract public function getResponseContent();
    
    /**
     * @var \luya\headless\Client
     */
    protected $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    protected $requestUrl;
    
    public function setEndpoint($endpoint)
    {
        $this->requestUrl = rtrim($this->client->serverUrl, '/') . '/' . ltrim($endpoint, '/');
    }
    
    public function getParsedResponse()
    {
        return json_decode($this->getResponseContent(), true);
    }
}