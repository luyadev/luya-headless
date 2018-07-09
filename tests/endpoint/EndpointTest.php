<?php

namespace luya\headless\tests\endpoint;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\Endpoint;

class EndpointTest extends HeadlessTestCase
{
    public function testGetEndpointName()
    {
        $name = new Endpoint();
        $this->assertSame('endpoint', $name->getEndpointName());
        $this->assertSame('api-admin-user', (new ApiAdminUser())->getEndpointName());
    }
    
    public function testInsert()
    {
        $test = new ApiAdminUser();
        $response = $test->insert(['foo' => 'bar'])->response($this->createDummyClient('{"foo":"bar"}'));
        $this->assertTrue($response->isSuccess());
    }
    
    public function testUpdate()
    {
        $test = new APiAdminUser();
        $request = $test->update(1, ['bar' => 'foo']);
        $response = $request->response($this->createDummyClient('{"bar":"foo"}'));
        $this->assertTrue($response->isSuccess());
        $this->assertSame(['bar' => 'foo'], $request->getArgs());
        $this->assertSame('api-admin-user/1', $request->getEndpoint());
    }
    
    public function testView()
    {
        $test = new APiAdminUser();
        $request = $test->view(1);
        $response = $request->response($this->createDummyClient('{"bar":"foo"}'));
        $this->assertTrue($response->isSuccess());
        $this->assertSame('api-admin-user/1', $request->getEndpoint());
    }
    
    public function testRemove()
    {
        $test = new APiAdminUser();
        $request = $test->remove(1);
        $response = $request->response($this->createDummyClient('{"bar":"foo"}'));
        $this->assertTrue($response->isSuccess());
        $this->assertSame('api-admin-user/1', $request->getEndpoint());
    }
}

class ApiAdminUser extends Endpoint
{
    
}