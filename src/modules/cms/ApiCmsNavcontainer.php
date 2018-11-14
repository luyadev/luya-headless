<?php

namespace luya\headless\modules\cms;

use luya\headless\ActiveEndpoint;

/**
 * Api CMS Nav Containers.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
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
