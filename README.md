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

## Intro

Quick intro about how to use the headless library with existing built in endpoints.

```php
// build client object with token and server infos
$client = new \luya\headless\Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html');

// run the pre-built EndpointRequest for the `admin/api-admin-lang` endpoint with the created client config.
$response = \luya\headless\endpoints\ApiAdminLang::find()->response($client);

// foreach trough the parsed json content from the api and dump the content.
foreach ($reponse->getContent() as $item) {
    var_dump($item);
}
```

See the [full Documentation](guide/README.md) in order to see how to make put, delete or post request, handle pagination or access the cms blocks.

## Documentation

[View the full Documentation](guide/README.md)

## Development and Contribution

+ PSR Naming convention: https://www.php-fig.org/bylaws/psr-naming-conventions/
+ Cache component require: https://www.php-fig.org/psr/psr-16/
+ Unit tests `composer install` and run `./vendor/bin/phpunit tests`