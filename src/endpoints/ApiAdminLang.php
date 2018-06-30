<?php

namespace luya\headless\endpoints;

use luya\headless\base\AbstractActiveEndpoint;

/**
 * Endpoint `api-admin-user` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminLang extends AbstractActiveEndpoint
{
    public $id;
    public $name;
    public $short_code;
    public $is_default;
    
    public function getEndpointName()
    {
        return 'admin/api-admin-lang';
    }
}