<?php

namespace luya\headless\base;

use luya\headless\Client;
use luya\headless\exceptions\MissingArgumentsException;
use luya\headless\base\EndpointInterface;
use luya\headless\base\AbstractRequestClient;

/**
 * EndpointRequest represents a request to a class with a response object in response().
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
abstract class AbstractEndpointRequest
{
    /**
     * @var EndpointInterface
     */
    protected $endpointObject;
    
    /**
     * Generate a reponse from a request.
     * 
     * @param AbstractRequestClient $request
     * @return EndpointResponse
     */
    abstract public function createResponse(AbstractRequestClient $request);
    
    /**
     *
     * @param EndpointInterface $endpoint
     */
    public function __construct(EndpointInterface $endpointObject)
    {
        $this->endpointObject = $endpointObject;
        $this->ensureRequiredArguments();
    }
    
    /**
     * Create a response.
     *
     * @param Client $client
     * @return EndpointResponse
     */
    public function response(Client $client)
    {
        $requestClient = $client->getRequestClient();
        $requestClient->setEndpoint($this->getEndpoint());
        
        if ($this->getCache()) {
            return $requestClient->getOrSetCache([$this->getEndpoint(), get_called_class()], $this->getCache(), function() use ($requestClient) {
                return $this->createResponse($requestClient);
            });
        }
        
        return $this->createResponse($requestClient);
    }
    
    private $_cache;
    
    /**
     * Set caching time for the current request.
     * 
     * If not set in client this won't have any effect, but will also not throw an exception.
     * 
     * @param integer $ttl Caching life time in seconds.$this
     * @return static
     */
    public function setCache($ttl)
    {
        $this->_cache = $ttl;
        
        return $this;
    }
    
    /**
     * Getter method for caching time.
     * 
     * @return integer
     */
    public function getCache()
    {
        return $this->_cache;
    }
    
    /**
     * Ensure whether the required args are provided or not.
     * @throws MissingArgumentsException
     */
    protected function ensureRequiredArguments()
    {
        foreach ($this->_requiredArgs as $key) {
            if (!array_key_exists($key, $this->_args)) {
                throw new MissingArgumentsException("Missing required arguments detected.");
            }
        }
    }

    private $_requiredArgs = [];

    /**
     * Set an array with argument keys which must be provided trough {{setArgs()}}.
     * 
     * Assuming your endpoint request must provide an `id` inside the arguments list, you
     * can require this by setting `setRequiredArgs(['id'])`. Now the EndpointRequest class
     * will check if `id` is in the given `getArgs()` list.
     * 
     * @return static
     */
    public function setRequiredArgs(array $args)
    {
        $this->_requiredArgs = $args;

        return $this;
    }
    
    private $_tokens;
    
    /**
     * A list of tokens which will be parsed while generating the endpointName. Example
     *
     * ```php
     * 'tokens' => [
     *     '{id}' => 1,
     *     '{name}' => 'foobar',
     * ];
     * ```
     *
     * You can now use the tokens in curly braced in the endpoint string like:
     *
     * ```php
     * setEndpoint('admin/api-user-login/{id}')
     * ```
     * 
     * There is also a predifend token {{endpointName}} which will represent the endpoint name
     * from the {{luya\headless\base\AbstractEndpoint::getEndpointName()}}.
     *
     * which would replace {id} with 1 from the tokens list.
     *
     * @param array $tokens
     * @return static
     */
    public function setTokens(array $tokens)
    {
        $this->_tokens = $tokens;
        
        return $this;
    }
    
    private $_endpoint;
    
    /**
     * Setter method in order to extend or override the endpoint name from the {{endpointObject}}.
     * 
     * @param string $name The endpoint name, in order to extend the current endpointName from the endpoint defintion you can use {endpointName}/foobar.
     * @return static
     */
    public function setEndpoint($name)
    {
        $this->_endpoint = $name;
        
        return $this;
    }
    
    /**
     * Getter method for endpoint name
     * @return string
     */
    public function getEndpoint()
    {
        $tokens = $this->_tokens;
        $tokens['{endpointName}'] = $this->endpointObject->getEndpointName();
        return $this->parseTokens($this->_endpoint ?: $this->endpointObject->getEndpointName(), $tokens);
    }

    /**
     * Parse tokens from a string.
     * @param string $string
     * @param array $tokens
     * @return mixed
     */
    protected function parseTokens($string, array $tokens)
    {
        return str_replace(array_keys($tokens), array_values($tokens), $string);
    }
    
    private $_args = [];
    
    /**
     * Setter method for arguments (params).
     *
     * Arguments are url parameters with key value pairing.
     *
     * @param array $args
     * @return static
     */
    public function setArgs(array $args)
    {
        $this->_args = array_merge($this->_args, $args);
        return $this;
    }
    
    /**
     * Getter method for arguments.
     * 
     * @return array
     */
    public function getArgs()
    {
        return $this->_args;
    }
    
    /**
     * Expand a given field or relation.
     * 
     * @see https://www.yiiframework.com/doc/guide/2.0/en/rest-resources#fields
     * @param array $extraFields
     * @return static
     */
    public function setExpand(array $extraFields)
    {
        return $this->setArgs(['expand' => implode(",", $extraFields)]);
    }
    
    /**
     * Set the current page which should be used.
     * 
     * @param integer $id
     * @return static
     */
    public function setPage($id)
    {
        return $this->setArgs(['page' => $id]);
    }
    
    /**
     * Set a value of how many items to response for every page.
     * 
     * @param integer $rows
     * @return static
     */
    public function setPerPage($rows)
    {
        return $this->setArgs(['per-page' => $rows]);
    }

    /**
     * Provides the option to return only a certain amount of fields.
     * 
     * This can speed up the request and reduce the transfer amount of data.
     * 
     * @see https://www.yiiframework.com/doc/guide/2.0/en/rest-resources#fields
     * @param array $fields
     * @return static
     */
    public function setFields(array $fields)
    {
        return $this->setArgs(['fields' => implode(",", $fields)]);
    }
    
    
    /**
     * Set a sort order for a given field.
     * 
     * ```php
     * setSort(['id' => SORT_ASC]);
     * ```
     * 
     * or the opposite way
     * 
     * ```php
     * setSort(['id' => SORT_DESC]);
     * ```
     * 
     * + SORT_ASC = 1,2,3
     * + SORT_DESC = 3,2,1
     * 
     * @param array $sort
     * @return static
     */
    public function setSort(array $sort)
    {
        $sortables = [];
        
        foreach ($sort as $field => $order) {
            $sortables[] = $order == SORT_ASC ? $field : '-' . $field;
        }
        
        return $this->setArgs(['sort' => implode(",", $sortables)]);
    }
    
    /**
     * Set filter conditions.
     * 
     * The filters must be enabled on the api side, otherwise it wont have any effect.
     * 
     * Example usage assuming filters are configured on api:
     * 
     * ```php
     * setFilter(['lang_id' => 1]); // like a where condition lang_id=1
     * ```
     * 
     * greather then, smaller then operators:
     * 
     * ```php
     * setFilter(['publication_date' => ['lt' => strtotime('tomorrow'), 'gt' => strtotime('yesterday')]);  //like >= and <= conditions for two fields.
     * ```
     * 
     * Example using the in condition for both languages:
     * 
     * ```php
     * setFilter([
     *     'lang_id' => ['in' => [1,2]]
     * ]);
     * ```
     * 
     * All posible operators:
     * 
     * + and (AND)
     * + or (OR)
     * + not (NOT)
     * + lt (<)
     * + gt (>)
     * + lte (<=)
     * + gte (>=)
     * + eq (=)
     * + neq (!=)
     * + in (IN)
     * + nin (NOT IN)
     * + like (LIKE)
     * 
     * ## Conditions
     * 
     * combine two conditions with which are AND conditions:
     * 
     * ```php
     * setFilter([
     *     'publication_date' => ['lt' => strtotime('tomorrow'), 'gt' => strtotime('yesterday')],
     *     'lang_id' => 2
     * ]);
     * ```
     * 
     * Two conditions but connected as OR condition:
     * 
     * ```php
     * setFilter([
     *    'or' => [
     *       ['lang_id' => 1],
     *       ['publication_date' => ['gt' => time()]],
     *    ]
     * ])
     * ``` 
     * 
     * @see https://www.yiiframework.com/doc/api/2.0/yii-data-datafilter
     * @return static
     */
    public function setFilter(array $filter)
    {
        return $this->setArgs(['filter' => $filter]);
    }
}