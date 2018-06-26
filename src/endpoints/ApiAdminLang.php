<?php

namespace luya\headless\endpoints;

use luya\headless\base\AbstractEndpoint;

/**
 * Endpoint `api-admin-user` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminLang extends AbstractEndpoint
{
    public function getEndpointName()
    {
        return 'admin/api-admin-lang';
    }
}