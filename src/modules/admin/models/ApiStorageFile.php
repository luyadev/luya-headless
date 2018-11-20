<?php

namespace luya\headless\modules\admin\models;

use luya\headless\base\BaseModel;
use luya\headless\ActiveEndpoint;
use luya\headless\Exception;
use luya\headless\endpoint\ActiveEndpointRequest;
use luya\headless\tests\ApiAdminUser;

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
    public $source;
    public $caption;

    // expand
    public $sizeReadable;
    public $isImage;

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
        return (new ActiveEndpointRequest(new static))->setEndpoint('{endpointName}/file')->setDefaultExpand(['source', 'caption'])->setArgs(['id' => $id]);
    }

    private $_images = [];

    public function setImages($images)
    {
        $this->_images = (array) $images;
    }

    public function getImage()
    {
        return ApiStorageImage::iterator($this->_images);
    }

    private $_user;

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getUser()
    {
        return new ApiAdminUser($this->_user);
    }
}
