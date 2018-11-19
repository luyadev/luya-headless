<?php

namespace luya\headless\modules\admin\models;

use luya\headless\base\BaseModel;
use luya\headless\ActiveEndpoint;
use luya\headless\Exception;

/**
 * Admin Storage Image Model.
 *
 * Expands:
 * + source
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiStorageImage extends ActiveEndpoint
{
    public $id;
    public $file_id;
    public $filter_id;
    public $resolution_width;
    public $resolution_height;
    public $source;

    public function getEndpointName()
    {
        return '{{%api-admin-storage}}';
    }

    public static function find()
    {
        throw new Exception("find() is not supported.");
    }

    public static function view($id)
    {
        return self::get()->setEndpoint('{endpointName}/image-info')->setArgs(['id' => $id]);
    }
}
