<?php

namespace luya\headless;

use Curl\Curl;

class Request
{
    /**
     * @var \luya\headless\Client
     */
    protected $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;    
    }

    public function appendEndpoint($endpoint)
    {
        return rtrim($this->client->serverUrl, '/') . '/' . ltrim($endpoint, '/');
    }
    
    /**
     * 
     * @param string $endpoint
     * @param array $data
     * @return \Curl\Curl
     */
    public function get($endpoint, array $data = [])
    {
        return (new Curl())->get($this->appendEndpoint($endpoint), $data);
    }
    
    public function post($endpoint, array $data = [])
    {
        
    }

    public function put($endpoint, array $data = [])
    {
        
    }

    public function patch($endpoint, array $data = [])
    {
        
    }
    
    public function delete($endpoint, array $data = [])
    {
        
    }
    
    public function parseResponse(Curl $curl)
    {
        return json_decode($curl->response);
    }
}