<?php

namespace luya\headless\modules\admin;

use luya\headless\Endpoint;
use luya\headless\Client;

/**
 * Api Admin Storage.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminStorage extends Endpoint
{
    /**
     * @inheritDoc
     */
    public function getEndpointName()
    {
        return '{{%api-admin-storage}}';
    }

    /**
     * Upload file into storage system.
     *
     * Upload example with Yii `UploadedFile`:
     *
     * ```php
     * $file = \yii\web\UploadedFile::getInstance($model, 'ad_image_id');
     * $model = ApiAdminStorage::fileUpload($client, $file->tempName, $file->type, $file->name);
     *
     * if ($model) {
     *     var_dump($model);
     * }
     * ```
     *
     * @param Client $client The client object.
     * @param string $source The path to the file (typical tmp_name from $_FILES)
     * @param string $type The file mime type (typical type from $_FILES)
     * @param string $name The name of the file (typicali name from $_FILES)
     * @param integer $folderId The folder id, if not given 0 is root directory.
     * @param boolean $isHidden Whether the file should be hidden in admin storage system or not.
     * @return ApiStorageFile
     */
    public static function fileUpload(Client $client, $source, $type, $name, $folderId = 0, $isHidden = true)
    {
        // ensure file exists and is file
        if (!file_exists($source) || !is_file($source)) {
            return false;
        }
        
        $file = [
            'file' => new \CurlFile($source, $type, $name),
            'isHidden' => $isHidden,
            'folderId' => $folderId,
        ];

        $upload = self::post()
            ->setEndpoint('{endpointName}/files-upload')
            ->setArgs($file)
            ->response($client);

        if (!$upload || $upload->isError()) {
            return false;
        }

        return new ApiStorageFile($upload->getContent()['file']);
    }

    /**
     * Image Upload
     *
     * Upload example with Yii `UploadedFile`:
     *
     * ```php
     * $file = \yii\web\UploadedFile::getInstance($model, 'ad_image_id');
     * $model = ApiAdminStorage::fileUpload($client, $file->tempName, $file->type, $file->name);
     *
     * if ($model) {
     *     var_dump($model);
     * }
     * ```
     *
     * @param Client $client The client object.
     * @param string $source The path to the file (typical tmp_name from $_FILES) /path/to/image.jpg
     * @param string $type The file mime type (typical type from $_FILES). e.g. image/jpg
     * @param string $name The name of the file (typicali name from $_FILES). e.g. MyFile.jpg
     * @param integer $folderId The folder id, if not given 0 is root directory.
     * @param boolean $isHidden Whether the file should be hidden in admin storage system or not.
     * @return ApiStorageImage
     */
    public static function imageUpload(Client $client, $source, $type, $name, $folderId = 0, $isHidden = true)
    {
        // ensure file exists and is file
        if (!file_exists($source) || !is_file($source)) {
            return false;
        }
        
        $file = [
            'file' => new \CurlFile($source, $type, $name),
            'isHidden' => $isHidden,
            'folderId' => $folderId,
        ];

        $upload = self::post()
            ->setEndpoint('{endpointName}/images-upload')
            ->setArgs($file)
            ->response($client);

        if (!$upload || $upload->isError()) {
            return false;
        }

        return new ApiStorageImage($upload->getContent()['image']);
    }
}
