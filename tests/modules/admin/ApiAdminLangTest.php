<?php

namespace luya\headless\tests\modules\admin;

use luya\headless\tests\ModuleActiveEndpointTestCase;

class ApiAdminLangTest extends ModuleActiveEndpointTestCase
{
    public $endpointModel = 'luya\headless\modules\admin\ApiAdminLang';
    
    public function getOneResponse($id)
    {
        return ['id' => $id, 'name' => 'English', 'short_code' => 'en', 'is_default' => 1];
    }
}
