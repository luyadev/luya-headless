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
        // example localhost token
        $client = new Client('559c2463503749662f7003bbaa48a86291e198a8832bf748a6af7e55acf953d7ep1Kw7B4ELGYu2KM7Tu8E6OZKV_sLdUE', 'http://localhost/luya-env-dev/public_html/en/admin');
        
        $request = $client->getRequest();
        $request->setEndpoint('api-admin-user');
        $request->get();
        
        $this->assertTrue($request->isSuccess());
        
        //var_dump($request->getParsedResponse());
    }
    
    public function testActiveQueryApiAdminUser()
    {
        // example localhost token
        $client = new Client('559c2463503749662f7003bbaa48a86291e198a8832bf748a6af7e55acf953d7ep1Kw7B4ELGYu2KM7Tu8E6OZKV_sLdUE', 'http://localhost/luya-env-dev/public_html/en/admin');
        
        $query = ApiAdminUser::find()->all($client);
        
        $this->assertTrue(is_array($query));
    }
}