<?php

namespace luya\headless\base;

use luya\headless\Client;

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
 * 
 * $model = MyUserModel::findOne(1, $client);
 * if ($model) {
 *     echo $model->firstname . ' ' . $model->lastname;
 * }
 * ```
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
        $response = self::view($id)->response($client);
        
        if ($response->isError()) {
            return false;
        }
        
        return new self($response->getContent());
    }
    
    /**
     * Find all items and generate an iterator with the given models.
     * 
     * @param Client $client
     * @return boolean|\luya\headless\base\BaseIterator
     */
    public static function findAll(Client $client)
    {
        $response = self::find()->response($client);
        
        if ($response->isError()) {
            return false;
        }
        
        return BaseIterator::create(get_called_class(), $response->getContent());
    }
}