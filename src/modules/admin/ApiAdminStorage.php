<?php

namespace luya\headless\modules\admin;

use luya\headless\Endpoint;

/**
 * Api Admin Storage.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminStorage extends Endpoint
{
    public function getEndpointName()
    {
        return '{{%api-admin-storage}}';
    }

    public static function getImage($id)
    {
        return self::get()->setEndpoint('{endpointName}/image-info')->setArgs(['id' => $id]);
    }

    public static function getFile($id)
    {
        return self::get()->setEndpoint('{endpointName}/file-info')->setArgs(['id' => $id]);
    }
}
