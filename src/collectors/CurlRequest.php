<?php

namespace luya\headless\collectors;

use Curl\Curl;
use luya\headless\base\AbstractRequest;

/**
 * Request Object via Curl.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class CurlRequest extends AbstractRequest
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
     * @inheritdoc
     */
    public function internalGet()
    {
        $this->curl = $this->getCurl()->get($this->getRequestUrl());
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function internalPost(array $data = [])
    {
        $this->curl = $this->getCurl()->post($this->getRequestUrl(), $data);
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function internalPut(array $data = [])
    {
        $this->curl = $this->getCurl()->put($this->getRequestUrl(), $data, true);
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function internalDelete(array $data = [])
    {
        $this->curl = $this->getCurl()->delete($this->getRequestUrl(), $data);
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function getResponseHeader($key)
    {
        return $this->curl->getResponseHeaders($key);
    }
    
    /**
     * @inheritdoc
     */
    public function isSuccess()
    {
        return $this->curl->isSuccess();
    }
    
    /**
     * @inheritdoc
     */
    public function getResponseStatusCode()
    {
        return $this->curl->http_status_code;
    }
    
    /**
     * @inheritdoc
     */
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
        return $this->curl->getResponseHeaders('x-cruft-length');
    }
}