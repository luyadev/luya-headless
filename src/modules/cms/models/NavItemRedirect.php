<?php

namespace luya\headless\modules\cms\models;

use luya\headless\base\BaseModel;

/**
 * Nav Item Redirect.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class NavItemRedirect extends BaseModel
{
    const TYPE_INTERNAL_PAGE = 1;
    
    const TYPE_EXTERNAL_URL = 2;
    
    const TYPE_LINK_TO_FILE = 3;
    
    const TYPE_LINK_TO_EMAIL = 4;
    
    const TYPE_LINK_TO_TELEPHONE = 5;
    
    public $id;
    public $type;
    public $value;

    public function getUrl()
    {
        return $this->value;
    }
}
