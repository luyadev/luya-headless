<?php

namespace luya\headless\modules\cms;

use luya\headless\base\BaseModel;
use luya\headless\modules\cms\models\NavItemModule;
use luya\headless\modules\cms\models\NavItemRedirect;
use luya\headless\base\BaseIterator;
use luya\headless\modules\cms\models\NavItem;
use luya\headless\modules\cms\models\Nav;
use luya\headless\modules\cms\models\NavItemPage;

/**
 * Represents a response object for a given Page.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class PageResponse extends BaseModel
{
    /**
     * @var integer Type page is 1
     */
    const TYPE_PAGE = 1;
    
    /**
     * @var integer Type module is 2
     */
    const TYPE_MODULE = 2;
    
    /**
     * @var integer Type redirect is 3
     */
    const TYPE_REDIRECT = 3;
    
    /**
     * @var boolean Whether current page response has an error or not.
     */
    public $error;
    
    private $_item;
    
    /**
     * Set the item.
     *
     * @param array $item
     */
    public function setItem(array $item)
    {
        $this->_item = new NavItem($item);
    }
    
    /**
     *
     * @return \luya\headless\modules\cms\models\NavItem
     */
    public function getItem()
    {
        return $this->_item;
    }
    
    private $_nav;
    
    /**
     * Set the nav data.
     *
     * @param array $nav
     */
    public function setNav(array $nav)
    {
        $this->_nav = new Nav($nav);
    }
    
    /**
     *
     * @return \luya\headless\modules\cms\models\Nav
     */
    public function getNav()
    {
        return $this->_nav;
    }

    private $_typeData;
    
    /**
     *
     * @param array $data
     */
    public function setTypeData(array $data)
    {
        $this->_typeData = $data;
    }
    
    /**
     * Whether current response is page.
     *
     * @return boolean
     */
    public function isPage()
    {
        return self::TYPE_PAGE == $this->item->nav_item_type;
    }
    
    /**
     * Whether current response is a redirect.
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return self::TYPE_REDIRECT == $this->item->nav_item_type;
    }
    
    /**
     * Whether current response is a module.
     *
     * @return boolean
     */
    public function isModule()
    {
        return self::TYPE_MODULE == $this->item->nav_item_type;
    }
    
    /**
     * Return an array with a NavItempages.
     *
     * @return \luya\headless\base\BaseIterator
     */
    public function getPageVersions()
    {
        return BaseIterator::create(NavItemPage::class, $this->_typeData, 'id');
    }
    
    /**
     * Get the current active page version.
     *
     * @return NavItemPage
     */
    public function getCurrentPageVersion()
    {
        $currentPageVersion = $this->_typeData[$this->item->nav_item_type_id];
        
        return new NavItemPage($currentPageVersion);
    }
    
    /**
     *
     * @return \luya\headless\modules\cms\models\NavItemRedirect
     */
    public function getRedirect()
    {
        return new NavItemRedirect($this->_typeData);
    }
    
    /**
     *
     * @return \luya\headless\modules\cms\models\NavItemModule
     */
    public function getModule()
    {
        return new NavItemModule($this->_typeData);
    }
}
