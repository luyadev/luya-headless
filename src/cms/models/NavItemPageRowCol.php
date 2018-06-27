<?php

namespace luya\headless\cms\models;

use luya\headless\base\BaseModel;
use luya\headless\base\BaseIterator;

class NavItemPageRowCol extends BaseModel
{
    public $cols;
    public $var;
    public $label;
    public $nav_item_page_id;
    public $prev_id;
    public $__nav_item_page_block_items;
    
    public function getSize()
    {
        return $this->cols;   
    }
    
    public function getItems()
    {
        return BaseIterator::create(NavItemPageBlock::class, $this->__nav_item_page_block_items, 'id');
    }
}