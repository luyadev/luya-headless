<?php

namespace luya\headless\endpoints;

use luya\headless\base\AbstractActiveEndpoint;

class ApiCmsNavcontainer extends AbstractActiveEndpoint
{
    public $id;
    public $name;
    public $alias;
    
    public function getEndpointName()
    {
        return 'admin/api-cms-navcontainer';
    }
}