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
        
        $query = ApiAdminUser::find()->all($client);
        
        $this->assertTrue(is_array($query));
    }

    /*
    public function testGetCmsMenuContainers()
    {
        $client = new Client('6238c3462600a2a14b8f952aee6e42f2f0e96fc875107529edec4fcaa1fe2ebf8a-ME-f6lOoK5_bGKF5rOgq5KueVSROV', 'http://localhost/luya-env-dev/public_html');

        $content = $client->getRequest()->setEndpoint('admin/api-cms-menu/items')->get(['langId' => 1, 'containerId' => 1])->getResponseRawContent();

        var_dump($content);


    }
    */
}