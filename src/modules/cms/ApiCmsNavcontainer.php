<?php

namespace luya\headless\modules\cms;

use luya\headless\ActiveEndpoint;

class ApiCmsNavcontainer extends ActiveEndpoint
{
    public $id;
    public $name;
    public $alias;
    
    public function getEndpointName()
    {
        return '{{%api-cms-navcontainer}}';
    }
}
