<?php

namespace luya\headless\tests;

use luya\headless\Client;
use luya\headless\collectors\DummyRequest;
use luya\testsuite\cases\WebApplicationTestCase;
use luya\headless\collectors\DummyRequestClient;
use luya\helpers\Json;

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
    
    /**
     *
     * @param Client $client
     * @param string $content
     * @param integer $success
     * @param array $headers
     * @return \luya\headless\collectors\DummyRequestClient
     */
    public function getDummyRequest(Client $client, $content, $success, array $headers = [])
    {
        $request = new DummyRequestClient($client);
        $request->response = is_array($content) ? Json::encode($content) : $content;
        $request->success = $success;
        $request->responseHeaderMapping = $headers;
        
        return $request;
    }
    
    /**
     *
     * @param string $content
     * @param boolean $success
     * @return \luya\headless\collectors\DummyRequestClient
     */
    public function getDummyClientRequest($content, $success = true)
    {
        return $this->getDummyRequest($this->getClient(), $content, $success);
    }

    /**
     *
     * @param string $content
     * @param boolean $success
     * @param integer $statusCode
     * @param array $headers
     * @return \luya\headless\Client
     */
    public function createDummyClient($content, $success = true, $statusCode = null, array $headers = [])
    {
        $client = $this->getClient();
        $request = $this->getDummyRequest($client, $content, $success, $headers);
        
        if ($statusCode) {
            $request->statusCode = $statusCode;
        }
        
        $client->setRequestClient($request);

        return $client;
    }
}
