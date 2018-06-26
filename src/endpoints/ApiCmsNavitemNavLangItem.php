<?php

namespace luya\headless\endpoints;

use luya\headless\BaseEndpoint;

/**
 * Endpoint `api-cms-menu/items` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiCmsNavitemNavLangItem extends BaseEndpoint
{
    public function requiredArguments()
    {
        return ['langId', 'navId'];
    }
    
    public function getEndpointName()
    {
        return 'admin/api-cms-navitem/nav-lang-item';
    }
}