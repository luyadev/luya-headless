<?php

namespace luya\headless\cms;

use luya\headless\base\BaseModel;
use luya\headless\cms\models\NavItem;
use luya\headless\cms\models\Nav;

/**
 * Get the content of a page.
 */
class PageResponse extends BaseModel
{
    public $error;
    
    private $_item;
    
    public function setItem(array $item)
    {
        $this->_item = new NavItem($item);
    }
    
    public function getItem()
    {
        return $this->_item;
    }
    
    private $_nav;
    
    public function setNav(array $nav)
    {
        $this->_nav = new Nav($nav);
    }
    
    public function getNav()
    {
        return $this->_nav;
    }

    private $_typeData;
    
    public function setTypeData(array $data)
    {
        var_dump($data);   
    }
}