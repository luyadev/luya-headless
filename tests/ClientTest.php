<?php

namespace luya\headless\tests;

use luya\headless\Client;
use luya\headless\endpoints\ApiAdminUser;

class ClientTest extends HeadlessTestCase
{
    public function testDefaultClientSettingsWithCurlRequest()
    {
        $client = new Client('123', 'http://luya.io/foobar');
        
        $request = $client->getRequest();
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
        $request = $client->getRequest();
        $request->setEndpoint('admin/admin-api-user');
        $this->assertSame('http://luya.io/barfoo/en/admin/admin-api-user', $request->getRequestUrl());
    }
    
    public function testRequestUrl()
    {
        $client = new Client('123', 'http://luya.io/barfoo');
        $request = $client->getRequest();
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
        $client->setRequest($this->getDummyRequest($client, '[{"id":1}]', true));
        
        $query = ApiAdminUser::find()->response($client);
        
        $this->assertSame([
            ['id' => 1]
        ], $query->getContent());
    }
}