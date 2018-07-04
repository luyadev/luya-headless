<?php

namespace luya\headless\modules\cms;

use luya\headless\Endpoint;

/**
 * Endpoint `api-cms-menu/items` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiCmsMenuItems extends Endpoint
{
    public function getEndpointName()
    {
        return 'admin/api-cms-menu/items';
    }

    /**
     * @inheritdoc
     */
    public static function index()
    {
        return parent::index()->setRequiredArgs(['langId', 'containerId']);
    }
}