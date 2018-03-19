<?php

namespace luya\headless;

abstract class BaseRequest
{
    abstract public function get(array $data = []);
    
    abstract public function post(array $data = []);
    
    abstract public function put(array $data = []);
    
    abstract public function patch(array $data = []);
    
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