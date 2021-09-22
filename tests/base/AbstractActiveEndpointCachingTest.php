<?php

namespace luya\headless\tests\base;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\tests\data\DummySimpleCache;
use luya\headless\cache\DynamicValue;
use luya\headless\Client;
use luya\headless\Endpoint;

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

    public function testRequestWithDummyCachingEnabled()
    {
        $client = new Client(null, 'https://jsonplaceholder.typicode.com');
        $client->setCache(new DummySimpleCache());
        
        $class = new class() extends Endpoint {
            public function getEndpointName()
            {
                return 'todos/1';
            }
        };

        $run1 = $class->get()->setCache(30)->response($client);

        $this->assertSame([
            'userId' => 1,
            'id' => 1,
            'title' => 'delectus aut autem',
            'completed' => false,
        ], $run1->getContent());

        $this->assertFalse($run1->requestClient->getIsCached());

        $run2 = $class->get()->setCache(30)->response($client);

        $this->assertTrue($run2->requestClient->getIsCached());

        $run2->requestClient->deleteCache('foobar');
    }

    public function testCachingOf404Request()
    {
        $client = new Client(null, 'https://jsonplaceholder.typicode.com');
        $client->strictCache = false;
        $client->setCache(new DummySimpleCache());
        
        $class = new class() extends Endpoint {
            public function getEndpointName()
            {
                return 'thisdoesnotexists/1';
            }
        };

        try {
            $run1 = $class->get()->setCache(30)->response($client);
        } catch (\Exception $e) {

        }
        $this->assertFalse($run1->requestClient->getIsCached());

        $run2 = $class->get()->setCache(30)->response($client);

        $this->assertTrue($run2->requestClient->getIsCached());
    }

    public function testCachingOf404RequestWithEnabledDoNotCacheBehavior()
    {
        $client = new Client(null, 'https://jsonplaceholder.typicode.com');
        $client->strictCache = true;
        $client->setCache(new DummySimpleCache());
        
        $class = new class() extends Endpoint {
            public function getEndpointName()
            {
                return 'thisdoesnotexistseither/1';
            }
        };

        try {
            $run1 = $class->get()->setCache(30)->response($client);
        } catch (\Exception $e) {

        }
        $this->assertFalse($run1->requestClient->getIsCached());

        $run2 = $class->get()->setCache(30)->response($client);

        $this->assertFalse($run2->requestClient->getIsCached());
    }
} 
