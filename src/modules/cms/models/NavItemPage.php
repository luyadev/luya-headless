<?php

namespace luya\headless\modules\cms\models;

use luya\headless\base\BaseModel;

/**
 * Nav Item Page.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class NavItemPage extends BaseModel
{
    public $id;
    public $layout_id;
    public $nav_item_id;
    public $timestamp_create;
    public $create_user_id;
    public $version_alias;
    public $contentAsArray;
    
    /**
     * @return NavItemPageRow
     */
    public function getRows()
    {
        if (empty($this->contentAsArray['__placeholders'])) {
            return [];
        }
        
        $models = [];
        foreach ($this->contentAsArray['__placeholders'] as $rowId => $cols) {
            $models[] = new NavItemPageRow(['index' => $rowId, 'cols' => $cols]);
        }
        
        return $models;
    }
}
