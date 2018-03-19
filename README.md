# luya-headless

A client library to access content from the LUYA APIs.

## Installation


## Usage

Make request with Client library:

```php
use luya\headless\Client;

$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html/admin');

$request = $client->request()->get('admin/api-admin-lang');

if ($request->isSuccess()) {
    $array = json_decode($request->content);
    
    foreach ($array as $item) {
        var_dump($item);
    }
    
} else {
    var_dump($request->error_message);
}
```

Using API wrappers (above example as short hand wrapper):

```php
use luya\headless\Client;
use luya\headless\endpoints\ApiAdminLang;

$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html/admin');

foreach (ApiAdminLang::find()->all($client) as $item) {
    var_dump($item);
}
```