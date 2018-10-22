# Pagination

An API Response can contain pagination informations in order to only a certain amount of items for a page. The API must return the Pagination headers `X-Pagination-Total-Count`, `X-Pagination-Page-Count`, `X-Pagination-Current-Page` and `X-Pagination-Per-Page`.

## Endpoint

A pagination example with `Endpoint`. Of course the API must support the Pagination X-Header data which is the default behavior for Yii Rest APIS:

```php
$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html');
$response = ApiAdminLang::find()->setPage($_GET['page'])->response($client);

foreach ($reponse->getContent() as $item) {
    var_dump($item);
}

// get informations about pagination of this crud:
echo $response->getTotalCount(); // number of total items in this crud
echo $response->getCurrentPage(); // the current page
echo $response->getPageCount(); // the number of pages
```

## Active Endpoint

A pagination example with `ActiveEndpoint`:

```php
$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html');
$response = ApiAdminLang::find()->setPage($_GET['page'])->all($client);
foreach ($reponse->getModels() as $item) {
    var_dump($item);
}

// get informations about pagination of this crud:
echo $response->getTotalCount(); // number of total items in this crud
echo $response->getCurrentPage(); // the current page
echo $response->getPageCount(); // the number of pages
```