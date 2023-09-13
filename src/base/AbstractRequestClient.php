<?php

namespace luya\headless\base;

use luya\headless\Client;
use luya\headless\exceptions\RequestException;
use luya\headless\Exception;
use luya\helpers\Json;

/**
 * Base Request is used to make the Request to the API.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
abstract class AbstractRequestClient
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
     * @return \luya\headless\base\AbstractRequestClient
     */
    abstract protected function internalGet();
    
    /**
     * Get request
     *
     * @param array $data
     * @return \luya\headless\base\AbstractRequestClient
     */
    abstract protected function internalPost(array $data = []);
    
    /**
     * Get request
     *
     * @param array $data
     * @return \luya\headless\base\AbstractRequestClient
     */
    abstract protected function internalPut(array $data = []);
    
    /**
     * Get request
     *
     * @param array $data
     * @return \luya\headless\base\AbstractRequestClient
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
     * @return string
     */
    abstract public function getResponseHeader($key);
    
    /**
     * Whether the connection library (curl) has an error or not.
     *
     * @return boolean
     */
    abstract public function hasConnectionError();
    
    /**
     * If there is a connection error hasConnectionError() this method returns the message.
     */
    abstract public function getConnectionErrorMessage();
    
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
     * @return \luya\headless\base\AbstractRequestClient
     */
    public function get(array $params = [])
    {
        $this->requestUrlParams = $params;
        $this->callBeforeRequestEvent($params, 'get');
        $this->internalGet();
        $this->callAfterRequestEvent($params, 'get');
        return $this;
    }
    
    /**
     *
     * @param array $data
     * @return \luya\headless\base\AbstractRequestClient
     */
    public function post(array $data = [])
    {
        $this->callBeforeRequestEvent($data, 'post');
        $this->internalPost($data);
        $this->callAfterRequestEvent($data, 'post');
        return $this;
    }
    
    /**
     *
     * @param array $data
     * @return \luya\headless\base\AbstractRequestClient
     */
    public function put(array $data = [])
    {
        $this->callBeforeRequestEvent($data, 'put');
        $this->internalPut($data);
        $this->callAfterRequestEvent($data, 'put');
        return $this;
    }
    
    /**
     *
     * @param array $data
     * @return \luya\headless\base\AbstractRequestClient
     */
    public function delete(array $data = [])
    {
        $this->callBeforeRequestEvent($data, 'delete');
        $this->internalDelete($data);
        $this->callAfterRequestEvent($data, 'delete');
        return $this;
    }
    
    /**
     *
     * @param array $data
     */
    protected function callBeforeRequestEvent(array $data, $type)
    {
        if ($this->client->getBeforeRequestEvent()) {
            call_user_func_array($this->client->getBeforeRequestEvent(), [new BeforeRequestEvent($this->getRequestUrl(), $data, $type)]);
        }
    }
    
    /**
     *
     * @param array $data
     */
    protected function callAfterRequestEvent(array $data, $type)
    {
        if ($this->client->getAfterRequestEvent()) {
            call_user_func_array($this->client->getAfterRequestEvent(), [new AfterRequestEvent($this->getRequestUrl(), $data, $this->getResponseStatusCode(), $this->getResponseRawContent(), $type, $this)]);
        }
    }
    
    /**
     * Setter method for endpoint.
     *
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $this->client->replaceEndpointPrefix($endpoint);

        return $this;
    }
    
    /**
     * Returns the full qualified request url from client serverUrl and endpoint.
     * @return string
     */
    public function getRequestUrl()
    {
        $parts = [rtrim((string) $this->client->serverUrl, '/'), $this->client->language, ltrim((string) $this->endpoint, '/')];
        
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
        // check for request client connection errors
        if ($this->hasConnectionError()) {
            throw new RequestException(sprintf('API request for "%s" could not resolved due to a connection error: "%s".', $this->getRequestUrl(), $this->getConnectionErrorMessage()));
        }
        
        // transform given status code to exceptions if needed.
        $this->responseStatusCodeExceptionCheck($this->getResponseStatusCode());
        
        return $this->jsonDecode($this->getResponseRawContent());
    }
    
    /**
     * Convert the raw json into an array and check for json errors.
     *
     * @return array
     * @throws RequestException
     */
    protected function jsonDecode($json)
    {
        if ($json === null || $json == '') {
            throw new RequestException(sprintf('API "%s" responded with an empty response content.', $this->getRequestUrl()));
        }

        $decode = json_decode((string) $json, true);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $decode;
            break;
            case JSON_ERROR_DEPTH:
                throw new RequestException(sprintf('API "%s" responded with invalid json: Maximum stack depth exceeded.', $this->getRequestUrl()));
            break;
            case JSON_ERROR_STATE_MISMATCH:
                throw new RequestException(sprintf('API "%s" responded with invalid json: Underflow or the modes mismatch.', $this->getRequestUrl()));
            break;
            case JSON_ERROR_CTRL_CHAR:
                throw new RequestException(sprintf('API "%s" responded with invalid json: Unexpected control character found.', $this->getRequestUrl()));
            break;
            case JSON_ERROR_SYNTAX:
                throw new RequestException(sprintf('API "%s" responded with invalid json: Syntax error, malformed JSON.', $this->getRequestUrl()));
            break;
            case JSON_ERROR_UTF8:
                throw new RequestException(sprintf('API "%s" responded with invalid json: Malformed UTF-8 characters, possibly incorrectly encoded.', $this->getRequestUrl()));
            break;
            default:
                throw new RequestException(sprintf('API "%s" responded with invalid json: Unknown error.', $this->getRequestUrl()));
            break;
        }
    }
    
    /**
     * Convert the status code into an exception if needed.
     *
     * @param integer $statusCode
     * @throws RequestException
     */
    public function responseStatusCodeExceptionCheck($statusCode)
    {
        if ($statusCode >= 500) {
            throw new RequestException(sprintf('API "%s" answered with a 500 server error. There must be a problem with the API server. Answer: %s', $this->getRequestUrl(), $this->getResponseRawContent()));
        }
        
        switch ($statusCode) {
            // handle unauthorized request exception
            case self::STATUS_CODE_UNAUTHORIZED:
                throw new RequestException(sprintf('Invalid access token provided or insufficient permission to access API "%s".', $this->getRequestUrl()));
            // handle forbidden request exception
            case self::STATUS_CODE_FORBIDDEN:
                throw new RequestException(sprintf('Insufficient permissions in order to access API "%s".', $this->getRequestUrl()));
            // handle not found endpoint request exception
            case self::STATUS_CODE_NOTFOUND:
                throw new RequestException(sprintf('Unable to find API "%s". Invalid endpoint name or serverUrl.', $this->getRequestUrl()));
        }
    }

    private $_cacheConfig = false;

    /**
     * Enable caching for this request with a given key and ttl.
     *
     * @param string $key
     * @param initeger $ttl
     * @since 2.6.0
     */
    public function enableCaching($key, $ttl)
    {
        $this->_cacheConfig = [$key, $ttl];
    }

    /**
     * Returns the key for the caching.
     *
     * @return string|boolean If caching is enabled, the key which will be used to cache will be returned.
     * @since 2.6.0
     */
    public function getCacheKey()
    {
        return $this->isCachingEnabled() ? $this->_cacheConfig[0] : false;
    }

    /**
     * Returns the time to life for the caching.
     *
     * @return integer|boolean If caching is enabled, the TTL value is returned.
     * @since 2.6.0
     */
    public function getCacheTtl()
    {
        return $this->isCachingEnabled() ? $this->_cacheConfig[1] : false;
    }

    /**
     * Determines whether the current request can be cached or not
     *
     * @return boolean
     * @since 2.6.0
     */
    public function isCachingEnabled()
    {
        return $this->_cacheConfig !== false;
    }

    private $_isCached = false;

    /**
     * Returns the status whether the current request has been cached or not
     *
     * @return integer
     * @since 2.6.0
     */
    public function getIsCached()
    {
        return $this->_isCached;
    }
    
    /**
     * Set the status of this request as cached.
     *
     * @since 2.6.0
     */
    public function setIsCached()
    {
        $this->_isCached = true;
    }
    
    /**
     * Get an existing key, or set a new value for the given cache key.
     *
     * @param array|string $key The cache key. If an array is given it will be parsed and md5 encoded, therefore it won't be possible to encode.
     * @param integer $ttl The number of seconds to store, its common that 0 is infinite.
     * @param callable $fn The function which evaluates the content. The callable must return an array with: http_status_code, request_headers, response_headers, response
     * @return mixed
     */
    protected function getOrSetCache($key, $ttl, callable $fn)
    {
        $cache = $this->client->getCache();
        
        if (!$cache) {
            return call_user_func($fn);
        }

        $content = $cache->get($key, false);

        if ($content !== false) {
            $this->setIsCached();
            return $content;
        }
        
        /** @var array $content The content is always an array with: http_status_code, request_headers, response_headers, response */
        $content = call_user_func($fn);
        
        // if status code is not 200, the response will not be cached if enaled.
        // otherwise it can happen that errors from the api will be cached for a long time.
        if ($this->client->strictCache && $content['http_status_code'] !== 200) {
            return $content;
        }

        if (!$cache->set($key, Json::encode($content, JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE), $ttl)) {
            throw new Exception("Unable to store the cache content for key '{$key}'.");
        }
        
        return $content;
    }

    /**
     * Delete certain key fro mcache
     *
     * @param string $key
     * @since 2.9.0
     */
    public function deleteCache($key)
    {
        $cache = $this->client->getCache();

        if ($cache) {
            $cache->delete($key);
        }
    }
}
