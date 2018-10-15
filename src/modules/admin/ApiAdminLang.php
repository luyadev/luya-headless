<?php

namespace luya\headless\modules\admin;

use luya\headless\ActiveEndpoint;

/**
 * Endpoint `api-admin-user` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminLang extends ActiveEndpoint
{
    public $id;
    public $name;
    public $short_code;
    public $is_default;
    
    public function getEndpointName()
    {
        return '{{%api-admin-lang}}';
    }
}
