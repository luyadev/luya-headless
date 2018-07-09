<?php

namespace luya\headless\tests;

use luya\headless\Client;
use luya\headless\collectors\DummyRequest;
use luya\testsuite\cases\WebApplicationTestCase;

abstract class HeadlessTestCase extends WebApplicationTestCase
{
    public function getConfigArray()
    {
        return [
           'id' => 'mytestapp',
           'basePath' => dirname(__DIR__),
        ];
    }
    
    public function getClient()
    {
        return new Client('MY_API_TOKEN', 'MY_ADMIN_SERVER_URL');
    }
    
    public function getDummyRequest(Client $client, $content, $success)
    {
        $request = new DummyRequest($client);
        $request->response = $content;
        $request->success = $success;
        
        return $request;
    }
    
    public function getDummyClientRequest($content, $success = true)
    {
        return $this->getDummyRequest($this->getClient(), $content, $success);
    }

    public function createDummyClient($content, $success = true, $statusCode = null)
    {
        $client = $this->getClient();
        $request = $this->getDummyRequest($client, $content, $success);
        
        if ($statusCode) {
            $request->statusCode = $statusCode;
        }
        
        $client->setRequest($request);

        return $client;
    }
}