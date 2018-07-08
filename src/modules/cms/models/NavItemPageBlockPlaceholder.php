<?php

namespace luya\headless\modules\cms\models;

use luya\headless\base\BaseModel;
use luya\headless\base\BaseIterator;

class NavItemPageBlockPlaceholder extends BaseModel
{
    public $var;    
    public $label;
    public $cols;
    public $nav_item_page_id;
    public $prev_id;
    public $__nav_item_page_block_items;

    /**
     * @return NavItemPageBlock
     */
    public function getBlocks()
    {
        return BaseIterator::create(NavItemPageBlock::class, $this->__nav_item_page_block_items, 'id');
    }
}