<?php

namespace luya\headless\tests\base;

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
        $client = $this->createDummyClient('{"id":1}');
        $this->expectException('luya\headless\Exception');
        $data = TestActiveEndpoint::testTokenUrl(123)->setCache(3600)->response($client);
        $this->assertSame(['id' => 1], $data->getContent());
    }
}