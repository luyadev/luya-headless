<?php

namespace luya\headless\tests\base;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\base\BaseModel;

class BaseModelTest extends HeadlessTestCase
{
    public function testLoad()
    {
        $model = new MyTestModel(['foo' => 1, 'bar' => 2]);

        $this->assertSame(1, $model->foo);
        $this->assertSame(2, $model->bar);

        // test setter, getter, has property and canGetProperty

        $this->assertTrue($model->hasProperty('foo'));
        $this->assertTrue($model->canGetProperty('foo'));

        $this->assertTrue($model->canGetProperty('read'));
        $this->assertTrue($model->canGetProperty('writeAndRead'));

        $this->assertTrue($model->canSetProperty('writeAndRead'));
        $this->assertFalse($model->canSetProperty('read'));

        $this->assertTrue($model->hasMethod('getRead'));
    }
}

class MyTestModel extends BaseModel
{
    public $foo;
    public $bar;

    public function getRead()
    {

    }

    public function getWriteAndRead()
    {

    }

    public function setWriteAndRead()
    {

    }
}