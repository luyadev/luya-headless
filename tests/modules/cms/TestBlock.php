<?php

namespace luya\headless\tests\modules\cms;

use luya\headless\modules\cms\AbstractBlockView;

class TestBlock extends AbstractBlockView
{
    public function render()
    {
        return '<p>'.$this->varValue('text').'</p>';
    }
}
