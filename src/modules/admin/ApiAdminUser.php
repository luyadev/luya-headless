<?php

namespace luya\headless\apis;

use luya\headless\base\AbstractActiveEndpoint;

/**
 * Endpoint `api-admin-user` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminUser extends AbstractActiveEndpoint
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    
    public function getEndpointName()
    {
        return 'admin/api-admin-user';
    }
}