<?php

namespace luya\headless\endpoints;

use luya\headless\BaseEndpoint;

/**
 * Endpoint `api-cms-menu/items` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiCmsMenuItems extends BaseEndpoint
{

    public function getEndpointName()
    {
        return 'admin/api-cms-menu/items';
    }
}