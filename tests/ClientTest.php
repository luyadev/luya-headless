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
    
    public function testExampleRequestChain()
    {
        $request = $this->getDummyClientRequest('[{"id":1}]', true);
        
        $this->assertSame([['id' => 1]], $request->get()->getParsedResponse());
    }
    
    public function testQueryApiAdminUser()
    {
        // example localhost token
        $client = $this->getClient();
        $client->setRequest($this->getDummyRequest($client, '[{"id":1}]', true));
        
        $query = ApiAdminUser::find()->all($client);
        
        $this->assertTrue(is_array($query));
    }
}