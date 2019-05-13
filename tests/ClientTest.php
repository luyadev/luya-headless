<?php

namespace luya\headless\tests;

use luya\headless\Client;
use luya\headless\modules\admin\ApiAdminUser;
use luya\headless\collectors\DummyRequest;
use luya\headless\base\BeforeRequestEvent;
use luya\headless\collectors\DummyRequestClient;
use luya\headless\base\AfterRequestEvent;

final class ClientTest extends HeadlessTestCase
{
    public function testDefaultClientSettingsWithCurlRequest()
    {
        $client = new Client('123', 'http://luya.io/foobar');
        
        $request = $client->getRequestClient();
        $request->setEndpoint('admin-api-user');
        $request->get();
        
        $this->assertFalse($request->isSuccess());
    }
    
    public function testExampleLocalhost()
    {
        $request = $this->getDummyClientRequest('foobar', true);
        
        $request->setEndpoint('api-admin-user');
        $request->get();
        
        $this->assertTrue($request->isSuccess());
    }
    
    public function testRequestUrlWithLanguage()
    {
        $client = new Client('123', 'http://luya.io/barfoo', 'en');
        $request = $client->getRequestClient();
        $request->setEndpoint('admin/admin-api-user');
        $this->assertSame('http://luya.io/barfoo/en/admin/admin-api-user', $request->getRequestUrl());
    }
    
    public function testRequestUrl()
    {
        $client = new Client('123', 'http://luya.io/barfoo');
        $request = $client->getRequestClient();
        $request->setEndpoint('admin/admin-api-user');
        $this->assertSame('http://luya.io/barfoo/admin/admin-api-user', $request->getRequestUrl());
    }
    
    
    public function testExampleRequestChain()
    {
        $request = $this->getDummyClientRequest('[{"id":1}]', true);
        $reponse = $request->get();
        $this->assertSame([['id' => 1]], $reponse->getParsedResponse());
        $this->assertSame('[{"id":1}]', $reponse->getResponseRawContent());
        $this->assertSame(200, $reponse->getResponseStatusCode());
    }
    
    public function testQueryApiAdminUser()
    {
        // example localhost token
        $client = $this->getClient();
        $client->setRequestClient($this->getDummyRequest($client, '[{"id":1}]', true));
        
        $query = ApiAdminUser::find()->response($client);
        
        $this->assertSame([
            ['id' => 1]
        ], $query->getContent());
    }
    
    public function testRequestCallback()
    {
        $client = new Client('token', 'url');
        $client->setBeforeRequestEvent(function (BeforeRequestEvent $event) {
            $this->assertSame('url?foo=bar', $event->url);
        });
        $client->setAfterRequestEvent(function (AfterRequestEvent $event) {
            $this->assertNull($event->content);
        });
        $client->setRequestClient(new DummyRequestClient($client));
        
        $this->expectException('luya\headless\exceptions\RequestException');
        $client->getRequestClient()->get(['foo' => 'bar'])->getParsedResponse();
    }
    
    public function testQuotePrefix()
    {
        $client = new Client('token', 'url');
        $client->endpointPrefix = 'admin/';
        
        $this->assertSame('foobar', $client->replaceEndpointPrefix('foobar'));
        $this->assertSame('foobar', $client->replaceEndpointPrefix('{{foobar}}'));
        $this->assertSame('foo bar', $client->replaceEndpointPrefix('{{foo bar}}'));
        $this->assertSame('Foo123Bar 123!', $client->replaceEndpointPrefix('{{Foo123Bar 123!}}'));
        $this->assertSame('admin/foobar', $client->replaceEndpointPrefix('{{%foobar}}'));
        $this->assertSame('{test}/foo/bar', $client->replaceEndpointPrefix('{test}/foo/{{bar}}'));
        $this->assertSame('{tenestedst}', $client->replaceEndpointPrefix('{te{{nested}}st}'));
        $this->assertSame('{aadmin/bc}', $client->replaceEndpointPrefix('{a{{%b}}c}'));
    }

    public function testGenerateCacheKey()
    {
        $client = new Client('token', 'url');
        $object = $client->getRequestClient();
        $cacheKey = '0.1.2-foo.bar.args.sort.0-0.1-x1.x2.y1.y2.hide1-0.1-hide2.0-hide2';
        $this->assertSame($cacheKey, $this->invokeMethod($client, 'generateCacheKey', [
            ['foo', 'bar', ['args' => ['x1', 'x2'], 'sort' => 'y1.y2', ['hide1' => ['hide2', ['hide2']]]]]
        ]));
    }

    public function testCacheKey()
    {
        $this->assertSame(123, CLient::cacheKey(123));
        $this->assertSame('abc', CLient::cacheKey('abc'));
        $this->assertSame(false, CLient::cacheKey(false));
        $this->assertSame('b88eb48e1b1dd1d02bcbe2403ce20f2b', CLient::cacheKey([1,2,3]));
    }

    public function testApplyCacheTimeAnomaly()
    {
        $client = new Client('token', 'url');
        $this->assertGreaterThan(1, $client->applyCacheTimeAnomaly(1));
        $this->assertSame(0, $client->applyCacheTimeAnomaly(0));
    }

    public function testDisabledApplyCacheTimeAnomaly()
    {
        $client = new Client('token', 'url');
        $client->cacheTimeAnomaly = false;
        $this->assertSame(1, $client->applyCacheTimeAnomaly(1));
        $this->assertSame(0, $client->applyCacheTimeAnomaly(0));
    }
}
