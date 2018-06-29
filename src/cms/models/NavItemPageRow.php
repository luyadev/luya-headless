<?php

namespace luya\headless\cms\models;

use luya\headless\base\BaseModel;
use luya\headless\base\BaseIterator;

class NavItemPageRow extends BaseModel
{
    public $index;
    
    private $_cols;
    
    public function setCols(array $cols)
    {
        $this->_cols = $cols;    
    }
    
    /**
     * 
     * @return NavItemPageRowCol
     */
    public function getCols()
    {
        return BaseIterator::create(NavItemPageRowCol::class, $this->_cols);
    }
}