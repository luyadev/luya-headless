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
    
    public function getResponseContent()
    {
        return $this->curl->response;
    }
}