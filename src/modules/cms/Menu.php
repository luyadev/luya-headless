<?php

namespace luya\headless\modules\cms;

use luya\headless\Client;
use luya\headless\base\BaseIterator;
use luya\headless\modules\cms\models\Nav;

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
    
    /**
     * Undocumented function
     *
     * @param [type] $containerId
     * @return static
     */
    public function container($containerId)
    {
        $this->_containerId = $containerId;
        return $this;
    }
    
    private $_langId;
    
    /**
     * Undocumented function
     *
     * @param [type] $langId
     * @return static
     */
    public function language($langId)
    {
        $this->_langId = $langId;
        return $this;
    }
    
    private $_parentNavId = 0;
    
    /**
     * Undocumented function
     *
     * @return static
     */
    public function root()
    {
        $this->_parentNavId = 0;
        return $this;
    }
    
    private $_cache;
    
    /**
     * Undocumented function
     *
     * @param [type] $ttl
     * @return static
     */
    public function cache($ttl)
    {
        $this->_cache = $ttl;
        
        return $this;
    }
    
    private $_data;
    
    /**
     * Undocumented function
     *
     * @param Client $client
     * @return array
     */
    protected function getData(Client $client)
    {
        if ($this->_data === null) {
            $this->_data = ApiCmsMenuItems::index()
                ->setArgs(['langId' => $this->_langId, 'containerId' => $this->_containerId])
                ->setCache($this->_cache)
                ->response($client)
                ->getContent();
        }
        
        return $this->_data;
    }
    
    /**
     *
     * @param Client $client
     * @return \luya\headless\modules\cms\models\Nav
     */
    public function response(Client $client)
    {
        $data = $this->getData($client);
        $items = isset($data[$this->_parentNavId]) ? $data[$this->_parentNavId] : [];

        return Nav::iterator($items, 'id');
    }
}
