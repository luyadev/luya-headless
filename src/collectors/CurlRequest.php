<?php

namespace luya\headless\collectors;

use luya\headless\BaseRequest;
use Curl\Curl;

class CurlRequest extends BaseRequest
{
    /**
     * @var \Curl\Curl
     */
    protected $curl;
    
    /**
     * 
     * @return \Curl\Curl
     */
    private function getCurl()
    {
        return (new Curl())
            ->setHeader('Authorization', 'Bearer '. $this->client->accessToken)
            ->setUserAgent('LUYA Headless Client (+https://github.com/luyadev/luya-headless)');
    }
    
    /**
     *
     * @param string $endpoint
     * @param array $data
     * @return \Curl\Curl
     */
    public function get(array $data = [])
    {
        $this->curl = $this->getCurl()->get($this->requestUrl, $data);
    }
    
    public function post(array $data = [])
    {
        $this->curl = $this->getCurl()->post($this->requestUrl, $data);
    }
    
    public function put(array $data = [])
    {
        $this->curl = $this->getCurl()->put($this->requestUrl, $data);
    }
    
    public function patch(array $data = [])
    {
        $this->curl = $this->getCurl()->patch($this->requestUrl, $data);
    }
    
    public function delete(array $data = [])
    {
        $this->curl = $this->getCurl()->delete($this->requestUrl, $data);
    }
    
    public function isSuccess()
    {
        return $this->curl->isSuccess();
    }
    
    public function getResponseContent()
    {
        return $this->curl->response;
    }
}