# Pagination

## Endpoint

A pagination example with `Endpoint`:

```php
$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html');
$response = ApiAdminLang::index()->setPage($_GET['page'])->response($client);

foreach ($reponse->getContent() as $item) {
    var_dump($item);
}

// get informations about pagination of this crud:
echo $response->getTotalCount(); // number of total items in this crud
echo $response->getCurrentPage(); // the current page
echo $response->getPageCount(); // the number of pages
```

## Active Endpoint

A pagination example with `ActiveEndpoint`:

```php
$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html');
$response = ApiAdminLang::find()->setPage($_GET['page])->all($client);
foreach ($reponse->getModels() as $item) {
    var_dump($item);
}

// get informations about pagination of this crud:
echo $response->getTotalCount(); // number of total items in this crud
echo $response->getCurrentPage(); // the current page
echo $response->getPageCount(); // the number of pages
```