<?php

namespace luya\headless\modules\admin;

use luya\headless\ActiveEndpoint;

/**
 * Endpoint `api-admin-user` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminUser extends ActiveEndpoint
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    
    public function getEndpointName()
    {
        return '{{%api-admin-user}}';
    }
}
