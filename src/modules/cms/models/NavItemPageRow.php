<?php

namespace luya\headless\modules\cms\models;

use luya\headless\base\BaseModel;
use luya\headless\base\BaseIterator;

/**
 * Nav Item Page Row.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
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
