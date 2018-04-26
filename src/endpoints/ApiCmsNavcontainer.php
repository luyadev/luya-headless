<?php

namespace luya\headless\endpoints;

use luya\headless\BaseEndpoint;

/**
 * Endpoint `api-cms-navcontainer` Query.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiCmsNavcontainer extends BaseEndpoint
{
    public function getEndpointName()
    {
        return 'admin/api-cms-navcontainer';
    }
}