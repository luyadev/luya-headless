<?php

namespace luya\headless\endpoints;

use luya\headless\base\AbstractEndpoint;

/**
 * Endpoint `api-cms-menu/items` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiCmsMenuItems extends AbstractEndpoint
{
    public function requiredArguments()
    {
        return ['langId', 'containerId'];
    }
    
    public function getEndpointName()
    {
        return 'admin/api-cms-menu/items';
    }
}