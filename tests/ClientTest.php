<?php

namespace luya\headless\tests;

use luya\headless\Client;
use luya\headless\modules\admin\ApiAdminUser;
use luya\headless\collectors\DummyRequest;
use luya\headless\base\BeforeRequestEvent;
use luya\headless\collectors\DummyRequestClient;

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
        
        $query = ApiAdminUser::index()->response($client);
        
        $this->assertSame([
            ['id' => 1]
        ], $query->getContent());
    }
    
    public function testRequestCallback()
    {
        $client = new Client('token', 'url');
        $client->setBeforeRequestEvent(function(BeforeRequestEvent $event) {
            $this->assertSame('url?foo=bar', $event->url);
        });
        $client->setRequestClient(new DummyRequestClient($client));
        
        $this->assertNull($client->getRequestClient()->get(['foo' => 'bar'])->getParsedResponse());
    }
}