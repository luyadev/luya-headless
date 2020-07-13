<?php

namespace luya\headless\tests\base;

use luya\headless\Client;
use luya\headless\Exception;
use luya\headless\tests\HeadlessTestCase;
use luya\headless\tests\data\DummySimpleCache;

class AbstractActiveEndpointCachingFailuresTest extends HeadlessTestCase
{
    public function getClient()
    {
        $cache = new DummySimpleCache();
        $cache->setReturn = false;
        
        $client = parent::getClient();
        $client->setCache($cache);
        return $client;
    }
    
    public function testFindTokenWithCacheWhichIsDisabled()
    {
        $client = new Client(null, 'https://jsonplaceholder.typicode.com');
        $cache = new DummySimpleCache();
        $cache->setReturn = false;
        $client->setCache($cache);
        $this->expectException(Exception::class);
        $data = TestActiveEndpoint::testTokenUrl(123)->setCache(3600)->response($client);
        $this->assertSame(['id' => 1], $data->getContent());
    }
}
