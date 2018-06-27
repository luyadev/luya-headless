# Custom endpoint requests

In order to make custom endpoint requests for a given custom url with paramters, whether is post get or patch use:

```php
<?php

namespace app\headless;

use luya\headless\base\AbstractEndpoint;

class ApiMymoduleUser extends AbstractEndpoint
{
    public function getEndpointName()
    {
        return 'admin/api-mymodule-user';
    }
    
    public static function login($email, $password)
    {
        return self::post()->setEndpoint('{endpointName}/login')->setArgs(['email' => $email, 'password' => $password]);
    }
}
```

The above example assumes you have an endpoint `admin/api-mymodule-user/login` where the post attributes email and password must be sent. Now you can easy use this in your Application like:

```php

$response = ApiMymoduleUser::login('basil@nadar.io', '12345678');

var_dump($response);
```
