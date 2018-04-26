<?php

namespace luya\headless\endpoints;

use luya\headless\BaseEndpoint;

/**
 * Endpoint `api-admin-user` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminUser extends BaseEndpoint
{
    public function getEndpointName()
    {
        return 'admin/api-admin-user';
    }
}