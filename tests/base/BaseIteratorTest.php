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

    public function testArrayAccces()
    {
        $data = [['firstname' => 'Foo', 'lastname' => 'Bar']];

        $object = BaseIterator::create(FooModel::class, $data);
        $object['new'] = 'yes';
        $this->assertSame('yes', $object['new']);
        $this->assertTrue(isset($object['new'])); // works
        $this->assertFalse(array_key_exists('new', $object)); // does not work with iterators
        $this->assertFalse(array_key_exists('firstname', $object));
        unset($object['new']);
        $this->assertFalse(isset($object['new']));
    }
}

class FooModel extends BaseModel
{
    public $firstname;
    public $lastname;   
}