<?php

namespace luya\headless\apis;

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