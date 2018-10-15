<?php

namespace luya\headless\tests\admin;

use luya\headless\tests\ModuleActiveEndpointTestCase;

class ApiAdminTagTest extends ModuleActiveEndpointTestCase
{
    public $endpointModel = 'luya\headless\modules\admin\ApiAdminTag';
    
    public function getOneResponse($id)
    {
        return ['id' => $id, 'name' => 'Tag Name!'];
    }
}
