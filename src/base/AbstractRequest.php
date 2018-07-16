<?php

namespace luya\headless\base;

use luya\headless\Client;
use luya\headless\exceptions\RequestException;

/**
 * Base Request is used to make the Request to the API.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
abstract class AbstractRequest
{
    const STATUS_CODE_UNAUTHORIZED = 401;
    
    const STATUS_CODE_FORBIDDEN = 403;
    
    const STATUS_CODE_NOTFOUND = 404;
    
    /**
     * @var array An array of get params which will be added as query to the requestUrl while creating.
     */
    protected $requestUrlParams = [];
    
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
     * @param array $params
     * @return \luya\headless\base\AbstractRequest
     */
    abstract protected function internalGet();
    
    /**
     * Get request
     *
     * @param array $data
     * @return \luya\headless\base\AbstractRequest
     */
    abstract protected function internalPost(array $data = []);
    
    /**
     * Get request
     *
     * @param array $data
     * @return \luya\headless\base\AbstractRequest
     */
    abstract protected function internalPut(array $data = []);
    
    /**
     * Get request
     *
     * @param array $data
     * @return \luya\headless\base\AbstractRequest
     */
    abstract protected function internalDelete(array $data = []);
    
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
     * Return the value for a given response header.
     *
     * @param string $key
     */
    abstract public function getResponseHeader($key);
    
    /**
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * 
     * @param array $params
     * @return \luya\headless\base\AbstractRequest
     */
    public function get(array $params = [])
    {
        $this->requestUrlParams = $params;
        $this->callBeforeRequestEvent($params);
        $this->internalGet();
        $this->callAfterRequestEvent($params);
        return $this;
    }
    
    /**
     * 
     * @param array $data
     * @return \luya\headless\base\AbstractRequest
     */
    public function post(array $data = [])
    {
        $this->callBeforeRequestEvent($data);
        $this->internalPost($data);  
        $this->callAfterRequestEvent($data);
        return $this;
    }
    
    /**
     * 
     * @param array $data
     * @return \luya\headless\base\AbstractRequest
     */
    public function put(array $data = [])
    {
        $this->callBeforeRequestEvent($data);
        $this->internalPut($data);
        $this->callAfterRequestEvent($data);
        return $this;
    }
    
    /**
     * 
     * @param array $data
     * @return \luya\headless\base\AbstractRequest
     */
    public function delete(array $data = [])
    {
        $this->callBeforeRequestEvent($data);
        $this->internalDelete($data);
        $this->callAfterRequestEvent($data);
        return $this;
    }
    
    /**
     * 
     * @param array $data
     */
    protected function callBeforeRequestEvent(array $data)
    {
        if ($this->client->getBeforeRequestEvent()) {
            call_user_func_array($this->client->getBeforeRequestEvent(), [new BeforeRequestEvent($this->getRequestUrl(), $data)]);
        }
    }
    
    /**
     * 
     * @param array $data
     */
    protected function callAfterRequestEvent(array $data)
    {
        if ($this->client->getAfterRequestEvent()) {
            call_user_func_array($this->client->getAfterRequestEvent(), [new AfterRequestEvent($this->getRequestUrl(), $data, $this->getResponseStatusCode(), $this->getResponseRawContent())]);
        }
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
        $parts = [rtrim($this->client->serverUrl, '/'), $this->client->language, ltrim($this->endpoint, '/')];
        
        $url = implode("/", array_filter($parts));

        if (!empty($this->requestUrlParams)) {
            $url.= '?'.http_build_query($this->requestUrlParams);
        }
        
        return $url;
    }
    
    /**
     * Parse and return the RAW content from {{getResponseRawContent()}} into an array structure.
     * 
     * @return array
     */
    public function getParsedResponse()
    {
        if ($this->getResponseStatusCode() >= 500) {
            throw new RequestException(sprintf('API "%s" answered with a 500 server error. There must be a problem with the API server.', $this->getRequestUrl()));
        }
        
        switch ($this->getResponseStatusCode()) {
            // handle unauthorized request exception
            case self::STATUS_CODE_UNAUTHORIZED:
                throw new RequestException(sprintf('Invalid access token provided or insufficient permission to access API "%s"."', $this->getRequestUrl()));
            // handle forbidden request exception
            case self::STATUS_CODE_FORBIDDEN:
                throw new RequestException(sprintf('insufficient permissions in order to access API "%s"', $this->getRequestUrl()));
            // handle not found endpoint request exception
            case self::STATUS_CODE_NOTFOUND:
                throw new RequestException(sprintf('Unable to find API "%s". Invalid endpoint name or serverUrl.', $this->getRequestUrl()));
        }
        
        return json_decode($this->getResponseRawContent(), true);
    }
    
    /**
     * Generate a cache key.
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    protected function generateCacheKey(array $params)
    {
        $params[] = __CLASS__;
        
        return implode(".", $params);
    }
    
    /**
     * Method to cache callable response content.
     *
     * @param array $key
     * @param string $ttl
     * @param callable $fn
     * @return mixed
     */
    public function getOrSetCache(array $key, $ttl, callable $fn)
    {
        $cache = $this->client->getCache();
        
        if (!$cache) {
            return call_user_func($fn);
        }
        
        $key = $this->generateCacheKey($key);
        
        if ($cache->has($key)) {
            return $cache->get($key);
        }
        
        $content = call_user_func($fn);
        
        $cache->set($key, $content, $ttl);
        
        return $content;
    }
}