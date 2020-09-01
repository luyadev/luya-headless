# Active Endpoint

Active Endpoint takes the inherited get, post, put & delete reoureces and transforms them to a task specific request types like from the REST structure defintion [See general infomrations](README.md):

|name|type|description
|----|----|-------
|find()|get|List all items `/users`.
|view($id)|get|Get a specific item  `/users/$id`.
|insert(array $data)|post|Create new record `/users`.
|update($id, array $data)|put|Update an existing record `/users/$id`.
|remove($id)|delete|Remove an existing record `/users/$id`.

An Active endpoint is similar to the ActiveRecord pattern. It extends the {{luya\headless\ActiveEndpoint}} by model loading ability in order to find single and multiple data sets for a given endpoint.

Example active endpoint with model attributes

```php
use luya\headless\ActiveEndpoint;

class ApiUser extends ActiveEndpoint
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

## Retrieve data

Now you can use the `viewOne()` and `findAll()` methods for the given Endpoint (ApiUser) in order to foreach all records as a model object or retrieve a single records.

```php
$client = new Client($token, $url);

foreach (ApiUser::findAll($client)->getModels() as $user) {
    echo $user->firstname . ' ' . $user->lastname;
}
```

In order to retrieve a single object use

```php
$client = new Client($token, $url);
$model = ApiUser::viewOne(1, $client);

if (!$model) {
    throw new Exception("Unable to find the given user.");
}

echo $model->firstname . ' ' . $model->lastname;
```

## Save / Update model

The built int `save()` methods allows you to create a new object or update an existing:

> Assuming the `getPrimaryKeys()` method is set correctly, by default its `id`.

### Insert a new record

```php
$client = new Client($token, $url);
$model = new ApiUser();
$model->firstname = 'Basil';
$model->username = 'nadar';

if ($model->save($client)) {
    echo "Its saved! nice!";
} else {
    // error while storing the model, output error messages
    var_dump($model->getErrors());
}
```

### Update existing record

```php
$model = ApiUser::viewOne(1, $client);
 
if ($model) {
    // echo current username
    echo $model->username;
    
    // change username and save
    $model->username = 'foobar';
    if ($model->save($client)) {
        echo "Its saved! nice!";
    } else {
        // error while storing the model, output error messages
        var_dump($model->getErrors());
    }
}
```

## ActiveEndpointQuery

In order to manipulate the sort, pagination or other request data you can also use:

```php
ApiUser::find()->setSort(['create_timestamp' => SORT_DESC])->all($client);
```
 
as find() returns the ActiveEndpointResponse Response object.
 
In order to extend an ActiveEndpoint by calling a custom action inside the api endpoint this could look like this:
 
```php
class MyTestApi extends ActiveEndpoint
{
    public $id;
    public $username;
    public $password;
    public $is_deleted;
    
    public function getEndpointName()
    {
        return 'admin/api-mymodule-test';
    }
    
    /**
     * Call the custom `admin/api-mymodule-test/find-by-username` endpoint and assign the value into the MyTestApi model. 
    */
    public static function findUser($username, Client $client)
    {
        return self::find()->setEndpoint('{endpointName}/find-by-username')->setArgs(['username' => $username])->one($client);
    }
 }
```
 
> The above example of course assumes that the same model is returned with the same properties, otherwise the values could not be assigned in the ActiveEndpoint.

Now you can access and update the given user:

```php
$model = MyTestApi::findUser('nadar', $client);

if ($model) {
    // echo current username
    echo $model->username;
    
    // change username and save
    $model->username = 'foobar';
    $model->save($client);
}
```

## Index By

When workin with the Active Endpoint the data is index by its primary key column defintion stored in {{luya\headless\ActiveEndpoint::getPrimaryKeys()}} in order to override the attribute which should be index by use `indexBy()`.

## Custom View Endpoint

Assuming you have a view endpoint which is `blog/preview/<ID>` which is different to the default view configuration `blog/<ID>` then you can create a customized view method:

```php
public static function preview($id)
{
    return parent::view($id)->setEndpoint('{endpointName}/preview/{id}');
}
```

This allows you to make calls for a `one` item with customized endpoint:

```php
$blog = Blog::preview($id)->one($client);
```

### Iteration

An example with iteration:

```php
class MyTestApi extends ActiveEndpoint
{
    public $id;
    public $username;
    public $password;
    public $is_deleted;
    
    public function getEndpointName()
    {
        return 'admin/api-mymodule-test';
    }
    
    /**
     * Call the `admin/api-mymodule-test` endpoint and add a sort param.
    */
    public static function indexByUsernames($Client $client)
    {
        return self::find()->setSort(['username' => SORT_ASC])->all($client);
    }
}
```

Using the method 

```php
$response = MyTestApi::indexByUsernames($client);

foreach ($response->getModels() as $model) {
    echo $model->username;
}
``` 
