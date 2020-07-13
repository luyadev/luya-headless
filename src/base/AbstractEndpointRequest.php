<?php

namespace luya\headless\base;

use luya\headless\Client;
use luya\headless\exceptions\MissingArgumentsException;
use luya\headless\base\EndpointInterface;
use luya\headless\base\AbstractRequestClient;
use luya\headless\endpoint\EndpointResponse;
use luya\headless\cache\DynamicValue;

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
    }

    public function getEndpointObject()
    {
        return $this->endpointObject;
    }
    
    /**
     * Create a response.
     *
     * @param Client $client
     * @return EndpointResponse
     */
    public function response(Client $client)
    {
        $this->ensureRequiredArguments();

        // clone request client object in order to ensure late binding of object wont override the client
        $requestClient = clone $client->getRequestClient();
        $requestClient->setEndpoint($this->getEndpoint());
        
        if ($this->getCache() !== false) {
            $cacheKey = $this->_cacheIdentifier ? $this->_cacheIdentifier : Client::cacheKey([$client->cachePrefix, $this->getEndpoint(), $this->_args, $client->language]);
           
            // set the information to the request client that caching is enabled for the given ttl with this key
            $requestClient->enableCaching($cacheKey, $client->applyCacheTimeAnomaly($this->getCache()));
        }
        
        return $this->createResponse($requestClient);
    }
    
    private $_cache = false;

    private $_cacheIdentifier;
    
    /**
     * Set caching time for the current request.
     *
     * If not set in client this won't have any effect, but will also not throw an exception.
     *
     * @param integer $ttl Caching life time in seconds. If 0 is used this is mostly an "infinte" value, like store the value until the whole cache or the key gets flushed.
     * @param string $identifier {@since 2.2.0} An optional identifier to use as cache key. Keep in mind that for language specific context you have to take by yourself. Its general recommend to avoid special chars
     * is it can lead into problems (for example symfony cache does not allow: {}()/\@). In order to use complex array keys to work with you can also use {{Client::cacheKey(['foo', 123, 'bar'])}} to generate unique
     * keys based on arrays.
     * @return static
     */
    public function setCache($ttl, $identifier = null)
    {
        $this->_cache = $ttl;
        $this->_cacheIdentifier = $identifier;
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
    
    private $_tokens = [];
    
    /**
     * A list of tokens which will be parsed while generating the endpointName. Example
     *
     * ```php
     * setTokens(['{id}' => 1, '{name}' => 'foobar']);
     * ```
     *
     * equals to:
     *
     * ```php
     * setTokens(['id' => 1, 'name' => 'foobar']);
     * ```
     *
     * > The curly braces will be added automatically if missing.
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
        $ensuredTokens = [];

        foreach ($tokens as $name => $value) {
            if (substr($name, 0, 1) !== '{') {
                $name = '{'.$name;
            }
            if (substr($name, -1) !== '}') {
                $name = $name .'}';
            }
            $ensuredTokens[$name] = $value;
        }

        $this->_tokens = array_merge($this->_tokens, $ensuredTokens);
        
        return $this;
    }
    
    private $_endpoint = '{endpointName}';
    
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
        $tokens = ['{endpointName}' => $this->endpointObject->getEndpointName()];
        return $this->parseTokens($this->_endpoint, array_merge($tokens, $this->_tokens));
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
     * In GET context those are Url Parameters, in POST context its sent als POST fields.
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
        return $this->noramlizeArgs($this->_args);
    }

    /**
     * Find DynamicValue objects in arguments list and return the value for those objects.
     *
     * @param array $values The arguments values to normalize.
     * @since 2.1.0
     * @return array
     */
    private function noramlizeArgs(array $values)
    {
        foreach ($values as $k => $v) {
            if ($v instanceof DynamicValue) {
                $values[$k] = $v->getValue();
            } elseif (is_array($v)) {
                $values[$k] = $this->noramlizeArgs($v);
            }
        }

        return $values;
    }
    
    private $_defaultExpand = [];

    /**
     * Set a default expand values.
     *
     * This should only used inside view, get, find methods in order to ensure a given expand is allways set.
     *
     * @param array $extraFields
     * @return static
     */
    public function setDefaultExpand(array $extraFields)
    {
        $this->_defaultExpand = $extraFields;

        return $this->setExpand([]);
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
        $extraFields = array_merge($this->_defaultExpand, $extraFields);

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

    private $_contentProcessor;

    /**
     * Process the endpoint response content (array parsed value).
     *
     * This allows you to interact with the content, for example if the models
     * are wrapped into an enclosed array key like `'items' => []`.
     *
     * ```php
     * public function setContentProcessor(function($content) {
     *  return $content['items'];
     * });
     * ```
     *
     * This allows you to either append the content process for a single request or for
     * all methods like find() view(). The below example will parsed all find calls:
     *
     * ```php
     * public static function find()
     * {
     *     return parent::find()->setContentProcessor(function($content) {
     *         return $content['items'];
     *     })->setExpand(['users']); // more default config for find commands
     * }
     * ```
     *
     * @return array
     */
    public function setContentProcessor(callable $fn)
    {
        $this->_contentProcessor = $fn;

        return $this;
    }

    /**
     * Call the content process with the given content.
     *
     * @param string $content The content to process.
     * @return string
     */
    public function callContentProcessor($content)
    {
        if ($this->_contentProcessor) {
            return call_user_func($this->_contentProcessor, $content);
        }

        return $content;
    }
}
