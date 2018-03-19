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
        
        //var_dump($request->getParsedResponse());
    }
    
    public function testActiveQueryApiAdminUser()
    {
        // example localhost token
        $client = $this->getClient();
        $client->setRequest($this->getDummyRequest($client, '[{"id":1}]', true));
        
        $query = ApiAdminUser::find()->all($client);
        
        $this->assertTrue(is_array($query));
    }
}