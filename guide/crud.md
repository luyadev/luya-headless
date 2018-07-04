## CRUD response

Make pagination with CRUD.

```php
use luya\headless\Client;
use luya\headless\endpoints\ApiAdminLang;

// build client object with token and server infos
$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html');

// run the pre-built EndpointRequest for the `admin/api-admin-lang` endpoint:
$response = ApiAdminLang::index()->response($client);

// get the parsed content (will parse json into array)
foreach ($reponse->getContent() as $item) {
    var_dump($item);
}

// get informations about pagination of this crud:

echo $response->getTotalCount(); // number of total items in this crud
echo $response->getCurrentPage(); // the current page
echo $response->getPageCount(); // the number of pages
```