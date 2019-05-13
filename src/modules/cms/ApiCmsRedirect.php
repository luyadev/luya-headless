<?php

namespace luya\headless\modules\cms;

use luya\headless\ActiveEndpoint;
use luya\headless\Client;

/**
 * Find redirect pages defined in redirects menu.
 *
 * ```php
 * $find = ApiCmsRediect('find/path', $client);
 *
 * if ($find) {
 *     // redirect to:
 *     header("Location: " .$find->redirect_path);
 * }
 * ```
 *
 * @since 1.1.0
 * @author Basil Suter <basil@nadar.io>
 */
class ApiCmsRedirect extends ActiveEndpoint
{
    public $id;
    public $timestamp_create;
    public $catch_path;
    public $redirect_path;
    public $redirect_status_code;
    
    public function getEndpointName()
    {
        return '{{%api-cms-redirect}}';
    }

    /**
     * Find a redirect for a certain path
     *
     * @param string $path The path to catch
     * @param Client $client
     * @return static
     */
    public static function catch($path, Client $client)
    {
        return self::find()->setEndpoint('{endpointName}/catch')->setArgs(['path' => $path])->one($client);
    }
}
