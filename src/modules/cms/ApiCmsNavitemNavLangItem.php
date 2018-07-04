<?php

namespace luya\headless\modules\cms;

use luya\headless\Endpoint;

/**
 * Get the page content for a given language and nav.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiCmsNavitemNavLangItem extends Endpoint
{
    public function getEndpointName()
    {
        return 'admin/api-cms-navitem/nav-lang-item';
    }

    /**
     * @inheritdoc
     */
    public static function index()
    {
        return parent::index()->setRequiredArgs(['langId', 'navId']);
    }
}