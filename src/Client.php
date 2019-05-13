<?php

namespace luya\headless;

use luya\headless\collectors\CurlRequestClient;
use luya\headless\base\AbstractRequestClient;
use Psr\SimpleCache\CacheInterface;
use luya\headless\cache\DynamicValue;

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
     * @var string An option for prefix all endpoint names. By default it contains the name of the LUYA admin where apis are assigned to.
     * In order to use the prefix an endpoint name must contain {{%my-api-endpoint}} this would be parsed to `admin/my-api-endpoint`. Endpoint
     * prefix are also very usefull when working with different api versions. `$endpointPrefix = 'v1/`.
     */
    public $endpointPrefix = 'admin/';
    
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
    
    
    private $_requestClient;
    
    /**
     * @return \luya\headless\base\AbstractRequestClient
     */
    public function getRequestClient()
    {
        if ($this->_requestClient === null) {
            $this->_requestClient = new CurlRequestClient($this);
        }

        return $this->_requestClient;
    }
    
    /**
     *
     * @param AbstractRequestClient $request
     */
    public function setRequestClient(AbstractRequestClient $request)
    {
        $this->_requestClient = $request;
    }
    
    /**
     * Replace endpoint Prefix with setting from client.
     *
     * Its very common to have a prefix for every api endpoint. Therefore you can prefix your
     * endpoint names with {{%my-api-endpoint}}. Assuming $endpointPrefix is `admin/` the result
     * would be `admin/my-api-endpoint`.
     *
     * @param string $endpointName The endpoint name to replace.
     * @return mixed
     */
    public function replaceEndpointPrefix($endpointName)
    {
        return preg_replace_callback('/\{\{(.*)\}\}/', function ($results) {
            $name = $results[1];
            
            if ($this->endpointPrefix) {
                $name = str_replace("%", $this->endpointPrefix, $name);
            }
            
            return $name;
        }, $endpointName);
    }
    
    private $_cache;
    
    /**
     * Setter method for CacheInterface.
     * 
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache)
    {
        $this->_cache = $cache;
    }
    
    /**
     * Getter method for Cache.
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

    /**
     * Generate a reproducable cache key based on input.
     * 
     * + If the key is a scalar value, this will be taken as key.
     * + If not a the array will be striped down into a scalar key which gets md5 encoded.
     *
     * This method is internally used to generate the cache key identifier in {{setCache()}} when no identifier
     * is given.
     * 
     * @param string|array $key An array with values or a scalar input type like a string or number.
     * @return string A scalar cache key
     * @since 2.2.0
     */
    public static function cacheKey($key)
    {
        return is_scalar($key) ? $key : md5(self::generateCacheKey($key));
    }
    
    /**
     * Generate a cache key.
     *
     * @param string $url
     * @param array $params
     * @return string The cache key string
     * @since 2.2.0
     */
    protected static function generateCacheKey(array $params)
    {
        foreach ($params as $key => $value) {
            if ($value instanceof DynamicValue) {
                $params[$key] = $value->getKey();
            } elseif (is_object($value)) {
                $params[$key] = get_class($value);
            } elseif (is_array($value)) {
                $params[$key] = static::generateCacheKey($value);
            }
        }
        
        return implode(".", array_keys($params)) . '-' . implode(".", array_values($params));
    }
}
