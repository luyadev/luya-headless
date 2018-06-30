<?php

namespace luya\headless\endpoints;

use luya\headless\base\AbstractEndpoint;

/**
 * Get the page content for a given language and nav.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiCmsNavitemNavLangItem extends AbstractEndpoint
{

    public function getEndpointName()
    {
        return 'admin/api-cms-navitem/nav-lang-item';
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return parent::find()->setRequiredArgs(['langId', 'navId']);
    }
}