<?php

namespace luya\headless;

use ReflectionClass;
use ReflectionProperty;
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
 * $model = MyUserModel::findOne(1, $client);
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
 */
class ActiveEndpoint extends Endpoint
{
    private $_isNewRecord = true;
  
    /**
     * @var boolean Whether the current ActiveEndpoint model is a new record or not.
     */
    public function getIsNewRecord()
    {
        return $this->_isNewRecord;
    }
    
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
    
    public function setErrors(array $errors)
    {
        $this->_errors = $errors;   
    }
    
    /**
     * An array with the primary key fields.
     * 
     * Im rare cases you have composite keys which are based on multiple fields.
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
     * if `userId` and `groupId` are set as primary keys, the return value would be `1,2`
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
     * Returns the list of attribute names.
     * By default, this method returns all public non-static properties of the class.
     * You may override this method to change the default behavior.
     * @return array list of attribute names.
     */
    public function attributes()
    {
        $class = new ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }
        
        return $names;
    }
    
    /**
     * Find object for the given id and returns the current active endpoint model attributes with the data.
     * 
     * @param integer $id
     * @param Client $client
     * @return ActiveEndpoint
     */
    public static function findOne($id, Client $client)
    {
        return static::find()->setTokens(['{id}' => $id])->setEndpoint('{endpointName}/{id}')->one($client);
    }
    
    /**
     * Find all items and generate an iterator with the given models.
     * 
     * @param Client $client
     * @return \luya\headless\endpoint\ActiveEndpointResponse
     */
    public static function findAll(Client $client)
    {
        return static::find()->all($client);
    }
    
    /**
     * 
     * @return ActiveEndpointRequest
     */
    public static function find()
    {
        return (new ActiveEndpointRequest(new static));    
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
            $request = self::insert($values);
        } else {
            $request = self::update($this->getPrimaryKeyValue(), $values);
        }
        
        $response = $request->response($client);
        
        if ($response->isSuccess()) {
            return true;
        }
        
        $this->errors = $response->getContent();
        
        return false;
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
}