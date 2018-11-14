<?php

namespace luya\headless\modules\cms\models;

use luya\headless\base\BaseModel;

/**
 * Nav.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Nav extends BaseModel
{
    public $id;
    public $nav_container_id;
    public $parent_nav_id;
    public $sort_index;
    public $is_deleted;
    public $is_hidden;
    public $is_home;
    public $is_offline;
    public $is_draft;
    public $layout_file;
    public $publish_from;
    public $publish_till;
    
    private $_item;
    
    public function setItem(array $item)
    {
        $this->_item = (new NavItem($item));
    }
    
    public function getItem()
    {
        return $this->_item;
    }
}
