<?php

namespace luya\headless\modules\admin\models;

use luya\headless\base\BaseModel;

/**
 * Admin Storage Image Model.
 *
 * Expands:
 * + source
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class StorageImage extends BaseModel
{
    public $id;
    public $file_id;
    public $filter_id;
    public $resolution_width;
    public $resolution_height;
    public $source;
}
