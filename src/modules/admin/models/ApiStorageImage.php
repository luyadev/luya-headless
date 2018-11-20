<?php

namespace luya\headless\modules\admin\models;

use luya\headless\base\BaseModel;
use luya\headless\ActiveEndpoint;
use luya\headless\Exception;
use luya\headless\endpoint\ActiveEndpointRequest;

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

    // expand
    public $thumbnail;
    public $tinyCropImage;
    public $mediumThumbnailImage;

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
        return (new ActiveEndpointRequest(new static))->setEndpoint('{endpointName}/image')->setArgs(['id' => $id]);
    }

    private $_file;

    public function getFile()
    {
        return new ApiStorageFile($this->_file);
    }

    public function setFile($file)
    {
        $this->_file = $file;
    }
}
