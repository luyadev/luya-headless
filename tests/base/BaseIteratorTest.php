<?php

namespace luya\headless\tests\base;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\base\BaseIterator;
use luya\headless\base\BaseModel;

class BaseIteratorTeste extends HeadlessTestCase
{
    public function testCreateWithWrongInputData()
    {
        $data = ['message' => 'error', 'status_code' => 123]; // An example for a none iteration error esponse

        $this->expectException("luya\headless\Exception");
        $iterator = BaseIterator::create(FooModel::class, $data);
    }

    public function testWithCorretModelInput()
    {
        $data = [['firstname' => 'Foo', 'lastname' => 'Bar']];

        $object = BaseIterator::create(FooModel::class, $data);

        $this->assertSame(1, count($object));

        foreach ($object as $value) {
            $this->assertSame('Foo', $value->firstname);
            $this->assertSame('Bar', $value->lastname);
        }
    }
}

class FooModel extends BaseModel
{
    public $firstname;
    public $lastname;   
}