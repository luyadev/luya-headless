# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/).

## 2.4.0

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
