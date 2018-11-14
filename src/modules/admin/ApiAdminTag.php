<?php

namespace luya\headless\modules\admin;

use luya\headless\ActiveEndpoint;
use luya\headless\Client;

/**
 * Get admin tags.
 *
 * Get all tags for a given relation table name:
 *
 * $tags = ApiAdminTag::table('table_name')->all($client);
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminTag extends ActiveEndpoint
{
    public $id;
    public $name;

    public function getEndpointName()
    {
        return '{{%api-admin-tag}}';
    }

    /**
     * Get all tags for a current table name.
     *
     * @param string $tableName
     * @return \luya\headless\endpoint\ActiveEndpointResponse
     */
    public static function table($tableName)
    {
        return self::find()->setEndpoint('{endpointName}/table')->setArgs(['tableName' => $tableName]);
    }
}
