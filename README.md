<p align="center">
  <img src="https://raw.githubusercontent.com/luyadev/luya/master/docs/logo/luya-logo-0.2x.png" alt="LUYA Logo"/>
</p>

# LUYA Headless Client

A client library to access content from the LUYA APIs.

[![LUYA](https://img.shields.io/badge/Powered%20by-LUYA-brightgreen.svg)](https://luya.io)
[![Latest Stable Version](https://poser.pugx.org/luyadev/luya-headless/v/stable)](https://packagist.org/packages/luyadev/luya-headless)
[![Total Downloads](https://poser.pugx.org/luyadev/luya-headless/downloads)](https://packagist.org/packages/luyadev/luya-headless)
[![Slack Support](https://img.shields.io/badge/Slack-luyadev-yellowgreen.svg)](https://slack.luya.io/)

## Installation

Add the LUYA headless client library to your composer.json:

```sh
composer require luyadev/luya-headless
```

## Usage

Make request with Client library:

```php
use luya\headless\Client;

// bild client object with token and server infos
$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html/admin');

// create get request for `api-admin-lang` endpoint
$request = $client->getRequest()->setEndpoint('api-admin-lang')->get();

// if successfull request, iterate over language items from `api-admin-lang` endpoint
if ($request->isSuccess()) {
    foreach ($request->getParsedResponse() as $item) {
        var_dump($item);
    }
}
```

Using API wrappers (above example as short hand wrapper):

```php
use luya\headless\Client;
use luya\headless\endpoints\ApiAdminLang;

// bild client object with token and server infos
$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html/admin');

// run the pre-built ActivQuery for the `api-admin-lang` endpoint
foreach (ApiAdminLang::find()->all($client) as $item) {
    var_dump($item);
}
```

## Todos

+ https://www.php-fig.org/psr/psr-6/