<?php

namespace luya\headless\cms;

use luya\headless\apis\ApiCmsMenuItems;
use luya\headless\Client;
use luya\headless\base\BaseIterator;
use luya\headless\cms\models\Nav;

/**
 * Generate menus trough the cms module.
 * 
 * @since 1.0.0
 * @author Basil Suter <basil@nadar.io>
 */
class Menu
{
    /**
     * @return self
     */
    public static function find()
    {
        return new static();
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
    
    protected function getData(Client $client)
    {
        if ($this->_data === null) {
            $this->_data = ApiCmsMenuItems::index()->setArgs(['langId' => $this->_langId, 'containerId' => $this->_containerId])->response($client)->getContent();
        }
        
        return $this->_data;
    }
    
    /**
     * 
     * @param Client $client
     * @return \luya\headless\cms\models\Nav
     */
    public function response(Client $client)
    {
        $items = $this->getData($client)[$this->_parentNavId];
        
        return BaseIterator::create(Nav::class, $items, 'id');
    }
}