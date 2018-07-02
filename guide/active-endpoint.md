## Active Endpoint

An Active endpoint is analog to Yii Frameworks ActiveRecord pattern. It extends the {{luya\headless\base\AbstractEndpoint}} by model loading ability in order to find single and multiple data sets for a given endpoint.

Example active endpoint with model attributes

```php
<?php

namespace app\headless;

use luya\headless\base\AbstractActiveEndpoint;

class ApiUser extends AbstractActiveEndpoint
{
    public $id;
    public $email;
    public $firstname;
    public $lastname;
    
    public function getEndpointName()
    {
        return 'admin/api-mymodel-user';
    }
}
```

Now you can use the `findOne()` and `findAll()` methods for the given Endpoint (ApiUser) in order to foreach all records as a model object or retrieve a single records.

```php
$client = new Client($token, $url);

foreach (ApiUser::findAll($client)->getModels() as $user) {
    echo $user->firstname . ' ' . $user->lastname;
}
```

In order to retrieve a single object use

```php
$client = new Client($token, $url);

$model = ApiUser::findOne(1, $client);

if (!$model) {
    throw new Exception("Unable to find the given user.");
}

echo $model->firstname . ' ' . $model->lastname;
```