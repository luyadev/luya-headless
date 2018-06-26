<?php

namespace luya\headless\endpoints;

use luya\headless\base\AbstractEndpoint;

/**
 * Endpoint `api-admin-user` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminUser extends AbstractEndpoint
{
    public function getEndpointName()
    {
        return 'admin/api-admin-user';
    }
}