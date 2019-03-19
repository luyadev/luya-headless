<?php

namespace luya\headless\tests\modules\admin;

use luya\headless\tests\ModuleActiveEndpointTestCase;

class ApiAdminUserTest extends ModuleActiveEndpointTestCase
{
    public $endpointModel = 'luya\headless\modules\admin\ApiAdminUser';
    
    public function getOneResponse($id)
    {
        return ['id' => $id, 'firstname' => 'John', 'lastname' => 'Doe', 'email' => 'john@nadar.io'];
    }
}
