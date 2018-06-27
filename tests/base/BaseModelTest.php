<?php

namespace luya\headless\tests\base;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\tests\data\TestModel;

class BaseModelTest extends HeadlessTestCase
{
    public function testLoad()
    {
        $model = new TestModel(['foo' => 1, 'bar' => 2]);

        $this->assertSame(1, $model->foo);
        $this->assertSame(2, $model->bar);
    }
}