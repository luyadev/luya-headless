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
        
        return new static($response->getContent());
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
        
        $models = BaseIterator::create(get_called_class(), $response->getContent());
        
        return new ActiveEndpointResponse($response, $models);
    }
}