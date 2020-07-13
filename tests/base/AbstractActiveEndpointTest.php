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

    public static function testBraceLessTokenUrl()
    {
        return self::find()->setTokens(['{firstname}' => 'john', 'lastname' => 'doe'])->setEndpoint('{endpointName}/{firstname}/{lastname}');
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

    public function testOneWithoutFindEndpontResourcesObject()
    {
        $client = $this->createDummyClient('{}');
        $this->assertFalse(TestActiveEndpoint::viewOne(1, $client));
    }

    public function testOneBut404ResponseCauseObjectNotExists()
    {
        $client = $this->createDummyClient('{}', false, 404);
        $this->assertFalse(TestActiveEndpoint::viewOne(1, $client));
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

        foreach ($data->getModels() as $model) {
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

    public function testFindFirstEmpty()
    {
        $client = $this->createDummyClient('[]');

        $this->assertFalse(TestActiveEndpoint::find()->first($client));
    }
    
    public function testFindFirstWithEmptyArray()
    {
        $client = $this->createDummyClient('[]');

        $this->assertFalse(TestActiveEndpoint::find()->first($client));
    }

    public function testFindToken()
    {
        $client = $this->createDummyClient('[{"id":1, "foo": "bar"}]');
        
        $data = TestActiveEndpoint::testTokenUrl(123)->response($client);
        
        $this->assertSame('MY_ADMIN_SERVER_URL/foo/bar/id/123/name', $data->requestClient->getRequestUrl());

        $url = TestActiveEndpoint::testBraceLessTokenUrl()->response($client);
        $this->assertSame('MY_ADMIN_SERVER_URL/foo/bar/john/doe', $url->requestClient->getRequestUrl());
    }
}
