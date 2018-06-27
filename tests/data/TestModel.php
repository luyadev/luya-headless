<?php

namespace luya\headless\tests\data;

use luya\headless\base\BaseModel;

class TestModel extends BaseModel
{
    public $foo;

    private $_bar;

    public function setBar($bar)
    {
            $this->_bar = $bar;
    }

    public function getBar()
    {
        return $this->_bar;
    }
}