<?php

namespace luya\headless\cms;

use luya\headless\base\BaseModel;
use luya\headless\cms\models\NavItem;
use luya\headless\cms\models\Nav;
use luya\headless\cms\models\NavItemPage;
use luya\headless\cms\models\NavItemModule;
use luya\headless\cms\models\NavItemRedirect;
use luya\headless\base\BaseIterator;

/**
 * Get the content of a page.
 */
class PageResponse extends BaseModel
{
    const TYPE_PAGE = 1;
    
    const TYPE_MODULE = 2;
    
    const TYPE_REDIRECT = 3;
    
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
        $this->_typeData = $data;
    }
    
    public function isPage()
    {
        return self::TYPE_PAGE == $this->item->nav_item_type;
    }
    
    public function isRedirect()
    {
        return self::TYPE_REDIRECT == $this->item->nav_item_type;
    }
    
    public function isModule()
    {
        return self::TYPE_MODULE == $this->item->nav_item_type;
    }
    
    public function getPageVersions()
    {
        return BaseIterator::create(NavItemPage::class, $this->_typeData, 'id');
    }
    
    public function getCurrentPageVersion()
    {
        $currentPageVersion = $this->_typeData[$this->item->nav_item_type_id];
        
        return new NavItemPage($currentPageVersion);
    }
    
    public function getRedirect()
    {
        return new NavItemRedirect($this->_typeData);
    }
    
    public function getModule()
    {
        return new NavItemModule($this->_typeData);
    }
}