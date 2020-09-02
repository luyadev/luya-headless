<p align="center">
  <img src="https://raw.githubusercontent.com/luyadev/luya/master/docs/logo/luya-logo-0.2x.png" alt="LUYA Logo"/>
</p>

# LUYA Headless Client

A client library to access content from the LUYA APIs (or any other REST API).

[![LUYA](https://img.shields.io/badge/Powered%20by-LUYA-brightgreen.svg)](https://luya.io)
![Tests](https://github.com/luyadev/luya-headless/workflows/Tests/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/luyadev/luya-headless/v/stable)](https://packagist.org/packages/luyadev/luya-headless)
[![Maintainability](https://api.codeclimate.com/v1/badges/c83c8a7c8d69f46a5e88/maintainability)](https://codeclimate.com/github/luyadev/luya-headless/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/c83c8a7c8d69f46a5e88/test_coverage)](https://codeclimate.com/github/luyadev/luya-headless/test_coverage)
[![Total Downloads](https://poser.pugx.org/luyadev/luya-headless/downloads)](https://packagist.org/packages/luyadev/luya-headless)

## Installation

Add the LUYA headless client library to your composer.json:

```sh
composer require luyadev/luya-headless
```

## Intro

Quick intro about how to use the headless library with a custom Endpoint: Create the Api Class (this very similar to Active Record pattern):

```php
class ApiCars extends \luya\headless\ActiveEdnpoint
{
    public $id;
    public $name;
    public $year;

    public function getEndpointName()
    {
        return '{{api-cars}}';
    }
}
```

With the new ApiCars class you can now insert, update or fetch data:

```php
use luya\headless\Client;

// build client object with token and server infos
$client = new Client('API_TOKEN', 'http://localhost/luya-kickstarter/public_html');

// create new value
$car = new ApiCars();
$car->name = 'BMW';
$car->year = 2019;
$car->save($client);

// find a given user by its ID
$car = ApiCars::viewOne(1, $client);
echo $car->name; // BMW
echo $car->year; // 2019

// update an existing value
$car->year = '2018';
$car->save($client);

// iterate all cars
$users = ApiCars::find()->setSort(['id' => SORT_ASC])->all($client);
foreach ($users->getModels() as $car) {
      echo $car->name;
}
```

## Documentation

See the [full Documentation](guide/README.md) in order to see how to make put, delete or post request, handle pagination or access the cms blocks.

+ [Documentation](guide/README.md)

## Development and Contribution

+ PSR Naming convention: https://www.php-fig.org/bylaws/psr-naming-conventions/
+ Cache component require: https://www.php-fig.org/psr/psr-16/ (example implementation, use: https://github.com/symfony/cache `new FilesystemCache('', 0, 'path/to/cache/folder');`)
+ Unit tests `composer install` and run `./vendor/bin/phpunit tests`
