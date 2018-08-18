<?php

namespace luya\headless;

use luya\headless\collectors\CurlRequest;
use luya\headless\base\AbstractRequest;
use Psr\SimpleCache\CacheInterface;

/**
 * Headless Client holds Configuration.
 * 
 * The Headless Client class holds the connection configuration as well as the headless configuration.
 * 
 * Every request to an API requeres an instance of Client, therefore for complex usage you should maybe store
 * the Client instance in a singleton object of your application.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Client
{
    /**
     * @var string The access token which is used for the bearer authentification.
     */
    public $accessToken;
    
    /**
     * @var string The base url for all requests.
     */
    public $serverUrl;
    
    /**
     * @var string The language short code which will be prepend to the server Url.
     */
    public $language;
    
    /**
     * @var boolean If enabled certain find request wont throw an exception but returns false or empty arrays. Therefore in production
     * you should disable debug (which is default).
     */
    public $debug = false;
    
    /**
     * 
     * @param string $accessToken
     * @param string $serverUrl Path to the webserver WITHOUT `admin`. Assuming your admin is accessable under `https://luya.io/admin` then the serverUrl would be `https://luya.io`.
     * @param string $language
     */
    public function __construct($accessToken, $serverUrl, $language = null)
    {
        $this->accessToken = $accessToken;
        $this->serverUrl = $serverUrl;
        $this->language = $language;
    }
    
    private $_request;
    
    /**
     * @return \luya\headless\base\AbstractRequest
     */
    public function getRequest()
    {
        if ($this->_request === null) {
            $this->_request = new CurlRequest($this);
        }

        return $this->_request;
    }
    
    /**
     * 
     * @param AbstractRequest $request
     */
    public function setRequest(AbstractRequest $request)
    {
        $this->_request = $request;
    }
    
    private $_cache;
    
    /**
     * 
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache)
    {
        $this->_cache = $cache;
    }
    
    /**
     * 
     * @return \Psr\SimpleCache\CacheInterface
     */
    public function getCache()
    {
        return $this->_cache;
    }
    
    private $_beforeRequestEvent;
    
    /**
     * A callable which runs before every request.
     *
     * ```php
     * setBeforeRequestEvent(function(luya\headless\base\BeforeRequestEvent $event) {
     *     // do some logging in your application
     * });
     * ```
     *
     * @param callable $fn
     */
    public function setBeforeRequestEvent(callable $fn)
    {
        $this->_beforeRequestEvent = $fn;
    }
    
    /**
     *
     * @return callable|null
     */
    public function getBeforeRequestEvent()
    {
        return $this->_beforeRequestEvent;
    }
    
    private $_afterRequestEvent;
    
    /**
     * A callable which runs after every request.
     * 
     * ```php
     * setAfterRequestEvent(function(luya\headless\base\AfterRequestEvent $event) {
     *     // do some logging in your application
     * });
     * ```
     * 
     * @param callable $fn
     */
    public function setAfterRequestEvent(callable $fn)
    {
        $this->_afterRequestEvent = $fn;      
    }
    
    /**
     * 
     * @return callable|null
     */
    public function getAfterRequestEvent()
    {
        return $this->_afterRequestEvent;
    }
}