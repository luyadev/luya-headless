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
composer require luyadev/luya-headless:^1.0@dev
```

## Usage

```php
use luya\headless\Client;
use luya\headless\endpoints\ApiAdminLang;

// build client object with token and server infos
$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html');

// run the pre-built EndpointRequest for the `admin/api-admin-lang` endpoint:
$response = ApiAdminLang::find()->response($client);

// get the parsed content (will parse json into array)
foreach ($reponse->getContent() as $item) {
    var_dump($item);
}

// get informations about pagination of this crud:

echo $response->getTotalCount(); // number of total items in this crud
echo $response->getCurrentPage(); // the current page
echo $response->getPageCount(); // the number of pages
```

## Todos

+ https://www.php-fig.org/psr/psr-6/

## Library rules

+ https://www.php-fig.org/bylaws/psr-naming-conventions/