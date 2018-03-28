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
        $client = new Client('34a40fd61a860231462386268e15bd80849c4ba84fc3bab3e52fc17f67ffbe84jLHCAOp7dLnCDcGTErBc068xibbM8KTW', 'http://localhost:8080/admin');

        $content = $client->getRequest()->setEndpoint('api-cms-menu/items')->get(['langId' => 1, 'containerId' => 1])->getResponseRawContent();

        var_dump($content);


    }
    */
}