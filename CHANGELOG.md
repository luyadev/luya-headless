# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/).

## 2.10.1 (13. September 2023)

+ Add php 8.3 to testing case
+ Ensure null is not provided in client request url (php 8.1 issue)

## 2.10.0 (11. January 2023)

+ Dropped support for php 7.0 and 7.1
+ Added json encoding when storing data in cache

## 2.9.1 (25. May 2022)

+ [#38](https://github.com/luyadev/luya-headless/pull/38) PHP 8.1 compatibility

## 2.9.0 (22. September 2021)

+ [#35](https://github.com/luyadev/luya-headless/pull/35) Added strict caching property which is by default enabled. Strict cache will only cache data which are from status 200 response code.

## 2.8.1 (18. August 2021)

+ Replace static calls with `static` instead of `self` in order to override the default behavior in final classes.

## 2.8.0 (29. December 2020)

+ [#33](https://github.com/luyadev/luya-headless/pull/33) Option to provide the `per-page` value when retrieving all pages with method `allPages(Client $client, $perPage = 50)`.
+ [#32](https://github.com/luyadev/luya-headless/pull/32) Prepared unit tests for PHP 8.0 and 7.0

## 2.7.0 (1. September 2020)

+ [#30](https://github.com/luyadev/luya-headless/pull/30) Added new `indexBy()` method to override primary key defintion while query data.

## 2.6.1 (13. August 2020)

+ Fixed an issue where storage file attributes where wrong declared `new_new` instead of `new_file` and `new_new_compound` instead of `name_new_compound`.

## 2.6.0 (14. July 2020)

+ [#29](https://github.com/luyadev/luya-headless/pull/29) Refactoring of the caching system. The CURL collection class serializes the response into a json and stores this information in the cache. The previous version stored the full PHP class object which can lead into errors when working with memcached servers or other PSR16 cache implementations.

## 2.5.0 (3. March 2020)

+ [#27](https://github.com/luyadev/luya-headless/issues/27) New sort() method for BaseIterator objects.

## 2.4.0 (25. Nov 2019)

+ [#26](https://github.com/luyadev/luya-headless/pull/26) Added new cache prefix key for Client objects.

## 2.3.0 (16. May 2019)

+ [#23](https://github.com/luyadev/luya-headless/issues/23) Add delete method with name `erase()` to delete an item from object context.
+ [#22](https://github.com/luyadev/luya-headless/issues/22) Make sure view(), update() and remove() works with composite keys provided as array.

## 2.2.0 (13. May 2019)

+ [#21](https://github.com/luyadev/luya-headless/issues/21) Added new `$cacheTimeAnomaly` property on the client in order to make sure multiple requests with same TTL settings won't unload & load together. This reduce the server load.
+ [#19](https://github.com/luyadev/luya-headless/issues/19) Add new parameter for `setCache($ttl, 'name')` in order to provide a cache name instead of an automatically generated one.

## 2.1.0 (5. May 2019)

+ [#18](https://github.com/luyadev/luya-headless/issues/18) Added dynamic value object in order to prevent cache keys with arguments which changes (like time expressions).

## 2.0.0 (19. April 2019)

+ **[BC BREAK] Changed signature and response of ApiAdminStorage::fileUpload() and ApiAdminStorage::imageUpload().** The methods return now the desired object instead of a PostResponse object.
+ Removed deprecated methods in ApiAdminStorage
+ Removed deprecated classes in `luya\headless\modules\models\*` folder.
+ [#17](https://github.com/luyadev/luya-headless/issues/17) Fixed bug where endpointName token was unable to override.

## 1.2.0 (19. March 2019)

+ Added new reload() method for ActiveEndpoints
+ Moved ApiStorageFile into admin module root folder as its now more then just a model.
+ Add file find() method to iterate trough files.
+ Added new methods for handling image filters.

## 1.1.0 (19. February 2019)

+ Added new `ApiCmsRedirect` Active Endpoint.

## 1.0.0 (30. November 2018)

+ First stable release.
