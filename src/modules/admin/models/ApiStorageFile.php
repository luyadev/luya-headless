<?php

namespace luya\headless\modules\admin\models;

use luya\headless\base\BaseModel;
use luya\headless\ActiveEndpoint;
use luya\headless\Exception;

/**
 * Admin Storage File Model.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiStorageFile extends ActiveEndpoint
{
    public $id;
    public $is_hidden;
    public $folder_id;
    public $name_original;
    public $new_new;
    public $new_new_compound;
    public $mime_type;
    public $extension;
    public $hash_file;
    public $hash_name;
    public $upload_timestamp;
    public $file_size;
    public $upload_user_id;
    public $is_deleted;
    public $passtrough_file;
    public $passtrough_file_password;
    public $passtrough_file_stats;
    public $inline_disposition;

    // expandable properties
    public $source;
    public $sizeReadable;
    public $user;
    public $file;
    public $images;
    public $isImage;
    public $caption;

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
        return self::get()->setEndpoint('{endpointName}/file-info')->setArgs(['id' => $id]);
    }
}
