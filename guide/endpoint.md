# Endpoint

## Basic call methods

Every endpoint has built in request methods for the current EndpointName defintion.

The basic request methods are:

|name|type|description
|----|----|-------
|get()|get|
|post()|post|
|put()|put|
|delete()|delete|

Which then are inherited by the already task specific request types like from the REST structure defintion [See general infomrations](README.md):

|name|type|description
|----|----|-------
|index()|get|List all items `/users`.
|view($id)|get|Get a specific item  `/users/$id`.
|insert(array $data)|post|Create new record `/users`.
|update($id, array $data)|put|Update an existing record `/users/$id`.
|remove($id)|delete|Remove an existing record `/users/$id`.

## Custom Endpoint Requests

In order to make custom endpoint requests for a given custom url with paramters, whether is post get or patch use:

```php
<?php

namespace app\headless;

use luya\headless\Endpoint;

class ApiMymoduleUser extends Endpoint
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
$resposne = ApiMymoduleUser::login('basil@nadar.io', '12345678')->response($client);

if ($resposne->isSuccess()) {
    // now you can log in the user ...
} else {
    // display the errors from the validation
    var_dump($login->getContent());
}
```

The implementation on API side could look like this:

```php
class UserController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'app\modules\mymodule\models\User';

    /**
     * Validate user
     */
    public function actionLogin()
    {
        $model = new UserLoginModel();
        $model->attributes = Yii::$app->request->post();
        
        if ($model->validate()) {
            return $model->user;
        }
        
        return $this->sendModelError($model);
    }
}
```

## Expand

Assumning you only want to expand for index() events you can also override the find method like this:

```php
public static function index()
{
    return parent::index()->setExpand(['users', 'image']);
}
```

If you want to expand every get request you could also override `get()`:

```php
public static function get()
{
    return parent::get()->setExpand(['users', 'image']);
}
```