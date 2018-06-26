<?php

namespace luya\headless\cms;

use luya\headless\endpoints\ApiCmsMenuItems;
use luya\headless\Client;

/**
 * Generate menus trough the cms module.
 * 
 * @since 1.0.0
 * @author Basil Suter <basil@nadar.io>
 */
class Menu
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    public static function find($client)
    {
        return new static($client);
    }
    
    private $_containerId;
    
    public function container($containerId)
    {
        $this->_containerId = $containerId;
        return $this;
    }
    
    private $_langId;
    
    public function language($langId)
    {
        $this->_langId = $langId;
        return $this;
    }
    
    private $_parentNavId = 0;
    
    public function root()
    {
        $this->_parentNavId = 0;    
        return $this;
    }
    
    private $_data;
    
    protected function getData()
    {
        if ($this->_data === null) {
            $this->_data = ApiCmsMenuItems::find()->args(['langId' => $this->_langId, 'containerId' => $this->_containerId])->response($this->client);
        }
        
        return $this->_data;
    }
    
    public function all()
    {
        return $this->getData()[$this->_parentNavId];
    }
}