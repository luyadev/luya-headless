<?php

namespace luya\headless\collectors;

use luya\headless\BaseRequest;
use Curl\Curl;

/**
 * Request Object via Curl.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
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
     * Get request
     *
     * @param array $data
     * @retunr \luya\headless\BaseRequest
     */
    public function get(array $data = [])
    {
        $this->curl = $this->getCurl()->get($this->getRequestUrl(), $data);
        
        return $this;
    }
    
    /**
     * Get request
     *
     * @param array $data
     * @retunr \luya\headless\BaseRequest
     */
    public function post(array $data = [])
    {
        $this->curl = $this->getCurl()->post($this->getRequestUrl(), $data);
        
        return $this;
    }
    
    /**
     * Get request
     *
     * @param array $data
     * @retunr \luya\headless\BaseRequest
     */
    public function put(array $data = [])
    {
        $this->curl = $this->getCurl()->put($this->getRequestUrl(), $data);
        
        return $this;
    }
    
    /**
     * Get request
     *
     * @param array $data
     * @retunr \luya\headless\BaseRequest
     */
    public function delete(array $data = [])
    {
        $this->curl = $this->getCurl()->delete($this->getRequestUrl(), $data);
        
        return $this;
    }
    
    
    
    public function isSuccess()
    {
        return $this->curl->isSuccess();
    }
    
    public function getResponseStatusCode()
    {
        return $this->curl->http_status_code;
    }
    
    public function getResponseRawContent()
    {
        $response = $this->curl->response;
        
        if ($this->getHasJsonCruftLength()) {
            $response = substr($response, $this->getHasJsonCruftLength());
        }
        
        return $response;
    }
    
    protected function getHasJsonCruftLength()
    {
        return $this->getResponseHeader('x-cruft-length');
    }
    
    protected function getResponseHeader($headerKey)
    {
        $headers = [];
        $headerKey = strtolower($headerKey);
        
        foreach ($this->curl->response_headers as $header) {
            $parts = explode(":", $header, 2);
            
            $key = isset($parts[0]) ? $parts[0] : null;
            $value = isset($parts[1]) ? $parts[1] : null;
            
            $headers[trim(strtolower($key))] = trim($value);
        }
        
        return isset($headers[$headerKey]) ? $headers[$headerKey] : false;
    }
}