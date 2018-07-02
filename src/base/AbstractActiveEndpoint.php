<?php

namespace luya\headless\base;

use luya\headless\Client;
use luya\headless\ActiveEndpointResponse;

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
abstract class AbstractActiveEndpoint extends AbstractEndpoint
{
    /**
     * @var boolean Whether the current ActiveEndpoint model is a new record or not.
     */
    public $isNewRecord = true;
    
    /**
     * @var array An array which can contain errors if validation for insert or update failes with invalud response status.
     */
    public $errors = [];
    
    /**
     * An array with the primary key fields.
     * 
     * Im rare cases you have composite keys which are based on multiple fields.
     * 
     * @return array An array with the primary keys.
     */
    public function getPrimaryKeys()
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
        foreach ($this->getPrimaryKeys() as $key) {
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
        return !empty($this->errors);    
    }
    
    /**
     * Find object for the given id and returns the current active endpoint model attributes with the data.
     * 
     * @param integer $id
     * @param Client $client
     * @return boolean|\luya\headless\base\AbstractActiveEndpoint
     */
    public static function findOne($id, Client $client)
    {
        $response = static::view($id)->response($client);
        
        if ($response->isError()) {
            return false;
        }
        
        $model = new static($response->getContent());
        $model->isNewRecord = false;
        return $model;
    }
    
    /**
     * Find all items and generate an iterator with the given models.
     * 
     * @param Client $client
     * @return boolean|\luya\headless\base\BaseIterator
     */
    public static function findAll(Client $client)
    {
        $response = static::find()->response($client);
        
        if ($response->isError()) {
            return [];
        }
        
        $models = BaseIterator::create(get_called_class(), $response->getContent(), $response->endpoint->getPrimaryKeys(), false);
        
        return new ActiveEndpointResponse($response, $models);
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
    public function save(Client $client, array $attributes)
    {
        $values = [];
        foreach ($attributes as $name) {
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
}