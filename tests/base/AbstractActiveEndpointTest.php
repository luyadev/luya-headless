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

    /**
     * 
     * @param unknown $id
     * @return \luya\headless\endpoint\AbstractEndpointRequest
     */
    public static function testTokenUrl($id)
    {
        return self::find()->setTokens(['{id2}' => $id])->setEndpoint('{endpointName}/id/{id2}/name');
    }
}

class AbstractActiveEndpointTest extends HeadlessTestCase
{
    public function testViewOne()
    {
        $client = $this->createDummyClient('{"id":1, "foo": "bar"}');

        $model = TestActiveEndpoint::viewOne(1, $client);

        $this->assertSame(1, $model->id);
        $this->assertSame("bar", $model->foo);
    }

    public function testCustomViewOne()
    {
        $client = $this->createDummyClient('{"id":1, "foo": "bar"}');

        $model = TestActiveEndpoint::view(1)->setPerPage(1)->one($client);

        $this->assertSame('bar', $model->foo);
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

    public function testFindFirst()
    {
        $client = $this->createDummyClient('[{"id":1, "foo": "bar"}]');

        $data = TestActiveEndpoint::find()->first($client);

        $this->assertSame('bar', $data->foo);
    }
    
    public function testFindToken()
    {
        $client = $this->createDummyClient('[{"id":1, "foo": "bar"}]');
        
        $data = TestActiveEndpoint::testTokenUrl(123)->response($client);
        
        $this->assertSame('MY_ADMIN_SERVER_URL/foo/bar/id/123/name', $data->request->getRequestUrl());
    }
    
    public function testFindTokenWithCacheWhichIsDisabled()
    {
        $client = $this->createDummyClient('{"id":1}');
        
        $data = TestActiveEndpoint::testTokenUrl(123)->setCache(3600)->response($client);
        
        $this->assertSame(['id' => 1], $data->getContent());
    }
}