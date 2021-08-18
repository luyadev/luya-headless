<?php

namespace luya\headless;

use luya\headless\endpoint\ActiveEndpointRequest;

/**
 * Abstract Active Endpoint class.
 *
 * A ActiveRecord similar pattern which will assigne the attribute from the api response data.
 *
 * ```php
 * class MyUserModel extends AbstractActiveEndpoint
 * {
 *     public $id;
 *     public $firstname;
 *     public $lastname;
 *     public $email;
 *
 *     public function getEndpointName()
 *     {
 *         return 'admin/api-mymodule-user';
 *     }
 * }
 * ```
 *
 * Now you can find the model data for the current model:
 *
 * ```php
 * $model = MyUserModel::viewOne(1, $client);
 * if ($model) {
 *     echo $model->firstname . ' ' . $model->lastname;
 * }
 * ```
 *
 * > When you have multiple endpoint implementions like finding on news but with enpoints for `latest` `trending` and
 * so on, the AbstractActiveEndpoint always assumes that you have the same response data model. Otherwise you have to
 * create a new abstract active endpoint response.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ActiveEndpoint extends Endpoint
{
    private $_isNewRecord = true;
  
    /**
     * Getter method for $isNewRecord.
     *
     * @return boolean Whether the current ActiveEndpoint model is a new record or not.
     */
    public function getIsNewRecord()
    {
        return $this->_isNewRecord;
    }
    
    /**
     * Setter method for $isNewRecord.
     *
     * @param boolean $state
     */
    public function setIsNewRecord($state)
    {
        $this->_isNewRecord = $state;
    }
    
    private $_errors = [];
    
    /**
     * Error response from the api.
     *
     * In general the error response is an array with a field and message key like:
     *
     * ```php
     * [
     *     ['field' => 'user_id', 'message' => 'The user id can not be empty.'],
     *     ['field' => 'timestamp', 'message' => 'The timestamp must be bigger then current timestamp.'],
     * ]
     * ```
     *
     * @return array An array which can contain errors if validation for insert or update failes with invalud response status.
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Get all error messages for a given attribute.
     *
     * This assumes the error response data is formtated as described in {{getErrors()}}.
     *
     * @param string $attribute the error message for a given attribute, if null all attributes with the corresponding message is returned.
     * @return array
     */
    public function getAttributeErrors($attribute = null)
    {
        $messages = [];
        foreach ($this->getErrors() as $error) {
            $messages[$error['field']][] = $error['message'];
        }

        return $attribute ? isset($messages[$attribute]) ? $messages[$attribute] : null : $messages;
    }
    
    /**
     * Assign the errors from the request.
     *
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->_errors = $errors;
    }
    
    /**
     * An array with the primary key fields.
     *
     * Im rare cases you have composite keys which are based on multiple fields.
     *
     * > Keep in mind to make sure to use the same order for the primary key fields as on the API. For example ['user_id', 'event_id'] would
     * > not generate the same composite key as ['event_id', 'user_id'].
     * 
     * @return array An array with the primary keys.
     */
    public static function getPrimaryKeys()
    {
        return ['id'];
    }
    
    /**
     * Returns a scalar represent on the primary key based on {{getPrimaryKeys()}}.
     *
     * If composite keys are required, the keys are seperated by a comma. Example
     *
     * ```php
     * ['userId' => 1, 'groupId' => 2];
     * ```
     *
     * if `userId` and `groupId` are set as primary keys, the return value would be `1,2`.
     *
     * @return string A scalar representation of the primary key value.
     */
    public function getPrimaryKeyValue()
    {
        $keys = [];
        foreach (static::getPrimaryKeys() as $key) {
            $keys[] = $this->{$key};
        }
        
        return implode(",", $keys);
    }
    
    /**
     * Whether current ActiveEndpoint has errors or not.
     *
     * @return boolean
     */
    public function hasError()
    {
        return !empty($this->getErrors());
    }
    
    /**
     * Create an {{ActiveEndpointRequest}} object.
     *
     * @return ActiveEndpointRequest
     */
    public static function find()
    {
        return (new ActiveEndpointRequest(new static));
    }

    /**
     * Find all items and generate an iterator with the given models.
     *
     * This is the short form for
     *
     * ```php
     * $models = Api::find()->all($client);
     * ```
     *
     * @param Client $client
     * @param integer $cacheTtl The number of seconds to chache the result. Works only if caching is configured in the Client.
     * @return \luya\headless\endpoint\ActiveEndpointResponse
     */
    public static function findAll(Client $client, $cacheTtl = null)
    {
        return static::find()->setCache($cacheTtl)->all($client);
    }
    
    /**
     * Find all items over all pages, this can be very slow and produce huge memory load
     *
     * @param Client $client
     * @param integer $cache The number of seconds to chache the result. Works only if caching is configured in the Client.
     * @return static
     */
    public static function findAllPages(Client $client, $cacheTtl = null)
    {
        return static::find()->setCache($cacheTtl)->allPages($client);
    }

    /**
     * Find object for the given id and returns the current active endpoint model attributes with the data.
     *
     * This is the short form of:
     *
     * ```php
     * $model = Api::view($id)->one($client);
     * ```
     *
     * @param integer|array $id The id to view. If an array is given it will be convereted to a composite key format like `1,4`.
     * @param Client $client
     * @return static
     */
    public static function viewOne($id, Client $client)
    {
        return static::view($id)->one($client);
    }
    
    /**
     * Represents the CRUD view request.
     *
     * Generates a view endpoint based response on a single object.
     *
     * ```php
     * $model = Api::view($id)->setExpand(['images'])->one($client);
     * ```
     *
     * If you want to define another view endpoint in additions you can achieve this by doing:
     *
     * ```php
     * public static function userImage($id)
     * {
     *     return parent::view($id)->setEndpoint('{endpointName}/{id}/image');
     * }
     * ```
     *
     * @param integer|array $id The id to view. If an array is given it will be convereted to a composite key format like `1,4`.
     * @return ActiveEndpointRequest
     */
    public static function view($id)
    {
        return static::find()->setTokens(['{id}' => implode(",", (array) $id)])->setEndpoint('{endpointName}/{id}');
    }

    /**
     * Represents the CRUD insert request.
     *
     * @param array $values
     * @return \luya\headless\endpoint\PostEndpointRequest
     */
    public static function insert(array $values)
    {
        return static::post()->setArgs($values);
    }
    
    /**
     * Represents the CRUD update request.
     *
     * @param integer|array $id The id to update. If an array is given it will be convereted to a composite key format like `1,4`.
     * @param array $values
     * @return \luya\headless\endpoint\PutEndpointRequest
     */
    public static function update($id, array $values)
    {
        return static::put()->setTokens(['{id}' => implode(",", (array) $id)])->setArgs($values)->setEndpoint('{endpointName}/{id}');
    }
    
    /**
     * Represents the CRUD remove/delete request.
     *
     * @param integer|array $id The id to remove. If an array is given it will be convereted to a composite key format like `1,4`.
     * @return \luya\headless\endpoint\DeleteEndpointRequest
     */
    public static function remove($id)
    {
        return static::delete()->setTokens(['{id}' => implode(",", (array) $id)])->setEndpoint('{endpointName}/{id}');
    }
    
    /**
     * Update or Insert model data.
     *
     * Runs {{udpate()}} or {{insert()}} request command based on current {{$isNewRecord}} state.
     *
     * @param Client $client
     * @param array $attributes
     * @return boolean
     */
    public function save(Client $client, array $attributes = [])
    {
        $values = [];
        $attrs = empty($attributes) ? $this->attributes() : $attributes;
        
        foreach ($attrs as $name) {
            $values[$name] = $this->{$name};
        }
        
        if ($this->isNewRecord) {
            $request = static::insert($values);
        } else {
            $request = static::update($this->getPrimaryKeyValue(), $values);
        }
        $response = $request->response($client);

        if ($response->isSuccess()) {
            $this->refresh($response->getContent());
            $this->setIsNewRecord(false);
            return true;
        }
        
        $this->errors = $response->getContent();
        
        return false;
    }

    /**
     * Delete a given model.
     * 
     * > delete() is used for delete requests, this method is named erase()
     *
     * @param Client $client
     * @return boolean Whether delete was successfull or not.
     * @since 2.3.0
     */
    public function erase(Client $client)
    {
        return static::remove($this->getPrimaryKeyValue())->response($client)->isSuccess();
    }    
    
    /**
     * Create an iterator of models for the current endpoint.
     *
     * @param array $data
     * @return \luya\headless\base\BaseIterator
     */
    public static function iterator(array $data, $keyColumn = null)
    {
        return parent::iterator($data, $keyColumn ?: static::getPrimaryKeys());
    }

    /**
     * Reload the current model with another call.
     *
     * This will reassign the values of the model with another call without any response on this function.
     *
     * In addition you can provided epxand parameters to re-load certain expand.
     *
     * @param Client $client
     * @param array $expand An array with expand params.
     * @since 1.2.0
     */
    public function reload(Client $client, array $expand = [])
    {
        $reload = static::view($this->getPrimaryKeyValue())->setExpand($expand)->response($client);

        $this->refresh($reload->getContent());
    }
}
