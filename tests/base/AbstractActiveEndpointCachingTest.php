<?php

namespace luya\headless\tests\base;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\tests\data\DummySimpleCache;
use luya\headless\cache\DynamicValue;

class AbstractActiveEndpointCachingTest extends HeadlessTestCase
{
    public function getClient()
    {
        $client = parent::getClient();
        $client->setCache(new DummySimpleCache());
        return $client;
    }
    
    public function testFindTokenWithCacheWhichIsDisabled()
    {
        $client = $this->createDummyClient('{"id":1}');
        $data = TestActiveEndpoint::testTokenUrl(123)->setCache(3600)->response($client);
        $this->assertSame(['id' => 1], $data->getContent());
    }

    public function testCachingSetException()
    {
        $client = $this->createDummyClient('{"id":1}');
        $data = TestActiveEndpoint::testTokenUrl(123)->setCache(3600)->response($client);
        $this->assertSame(['id' => 1], $data->getContent());
    }

    public function testSetCacheWithFixCacheKey()
    {
        $client = $this->createDummyClient('{"id":1}');
        $data = TestActiveEndpoint::testTokenUrl(123)->setCache(3600, 'foobar')->response($client);
        $this->assertSame(['id' => 1], $data->getContent());
    }

    public function testIfCacheCanBe0()
    {
        $client = $this->createDummyClient('{"id":1}');
        $request = TestActiveEndpoint::testTokenUrl(123)->setCache(0);
        $data = $request->response($client);
        $this->assertSame(['id' => 1], $data->getContent());

        $this->assertSame(0, $request->getCache());

        $this->assertFalse(TestActiveEndpoint::testTokenUrl(123)->getCache());
    }

    public function testCacheWithDynamicValue()
    {
        $client = $this->createDummyClient('{"id":1}');

        $r = TestActiveEndpoint::find()
            ->setCache(3600)
            ->setFilter(['xyz' => new DynamicValue('123')])
            ->setArgs(['param' => new DynamicValue(123)])
            ->response($client);

        $this->assertSame([
            'filter' => [
                'xyz' => '123',
            ],
            'param' => 123,
        ], $r->request->getArgs());


        $object = $client->getRequestClient();
        $this->assertSame('0.foo.sub-foo.3.0-4', $this->invokeMethod($client, 'generateCacheKey', [
            ['foo', 'foo' => new DynamicValue(123), 'sub' => [new DynamicValue('1234')]]
        ]));
    }
}
