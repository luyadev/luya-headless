<?php

namespace luya\headless\tests\base;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\base\BaseIterator;
use luya\headless\base\BaseModel;
use luya\helpers\ArrayHelper;

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
        $this->assertFalse(empty($object));

        foreach ($object as $value) {
            $this->assertSame('Foo', $value->firstname);
            $this->assertSame('Bar', $value->lastname);
        }
    }

    public function testEmptyIterator()
    {
        $object = BaseIterator::create(FooModel::class, []);

        $this->assertSame(0, count($object));
        $this->assertFalse(empty($object)); // its not true, because $object contains an object which is not empty by definition.
    }

    public function testArrayAccces()
    {
        $data = [['firstname' => 'Foo', 'lastname' => 'Bar']];

        $object = BaseIterator::create(FooModel::class, $data);
        $object['new'] = 'yes';
        $this->assertSame('yes', $object['new']);
        $this->assertTrue(isset($object['new'])); // works
        // @see https://stackoverflow.com/a/1538138/4611030
        //$this->assertFalse(array_key_exists('new', $object)); // does not work with iterators
        //$this->assertFalse(array_key_exists('firstname', $object));
        unset($object['new']);
        $this->assertFalse(isset($object['new']));
    }

    public function testSorting()
    {
        $data = [
            ['firstname' => 'A', 'lastname' => 'A'],
            ['firstname' => 'B', 'lastname' => 'B'],
        ];

        $object = BaseIterator::create(FooModel::class, $data);

        $this->assertSame($object[0]->firstname, 'A');
        $this->assertSame($object[1]->firstname, 'B');

        $object->rewind();
        $this->assertSame($object[0]->firstname, 'A');
        $this->assertSame($object[1]->firstname, 'B');

        $object->rewind();
        $object->sort(function($data) {
            ArrayHelper::multisort($data, 'firstname', SORT_DESC);
            return $data;
        });
        
        $this->assertSame($object[0]->firstname, 'B');
        $this->assertSame($object[1]->firstname, 'A');
    }
}

class FooModel extends BaseModel
{
    public $firstname;
    public $lastname;
}
