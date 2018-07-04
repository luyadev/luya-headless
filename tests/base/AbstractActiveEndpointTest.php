<?php

namespace luya\headless\tests\base;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\ActiveEndpoint;

final class TestActiveEndpoint extends ActiveEndpoint
{
    public $id;
    public $foo;

    public function getEndpointName()
    {
        return 'foo/bar';
    }
}

class AbstractActiveEndpointTest extends HeadlessTestCase
{
    public function testFindOne()
    {
        $client = $this->createDummyClient('{"id":1, "foo": "bar"}');

        $model = TestActiveEndpoint::findOne(1, $client);

        $this->assertSame(1, $model->id);
        $this->assertSame("bar", $model->foo);
    }

    public function testFindAll()
    {
        $client = $this->createDummyClient('[{"id":1, "foo": "bar"}]');

        $data = TestActiveEndpoint::findAll($client);

        foreach ($data->getModels() as $model)
        {
            $this->assertSame(1, $model->id);
            $this->assertSame("bar", $model->foo);
        }
    }
}