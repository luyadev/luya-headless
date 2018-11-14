<?php

namespace luya\headless\modules\cms\models;

use luya\headless\base\BaseModel;
use luya\headless\base\BaseIterator;

/**
 * Nav Item Page Row Col.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
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
    
    /**
     * @return NavItemPageBlock
     */
    public function getBlocks()
    {
        return BaseIterator::create(NavItemPageBlock::class, $this->__nav_item_page_block_items, 'id');
    }
}
