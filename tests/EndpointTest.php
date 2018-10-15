<?php

namespace luya\headless\tests;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\Endpoint;

class EndpointTest extends HeadlessTestCase
{
    public function testGetEndpointName()
    {
        $name = new Endpoint();
        $this->assertSame('{{%endpoint}}', $name->getEndpointName());
        $this->assertSame('{{%api-admin-user}}', (new ApiAdminUser())->getEndpointName());
    }
}

class ApiAdminUser extends Endpoint
{
}
