<?php

namespace luya\headless\tests\data;

use luya\headless\ActiveEndpoint;

/**
 * @see https://jsonplaceholder.typicode.com/users
 */
class DummyJsonplaceholderUsers extends ActiveEndpoint
{
    public $id;
    public $name;
    public $username;

    public function getEndpointName()
    {
        return 'users';
    }
}