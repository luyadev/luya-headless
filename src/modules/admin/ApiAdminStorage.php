<?php

namespace luya\headless\modules\admin;

use luya\headless\Endpoint;

/**
 * Api Admin Storage.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ApiAdminStorage extends Endpoint
{
    public function getEndpointName()
    {
        return '{{%api-admin-storage}}';
    }

    public static function getImage($id)
    {
        trigger_error("Use ApiStorageImage::viewOne()", E_USER_DEPRECATED);

        return self::get()->setEndpoint('{endpointName}/image-info')->setArgs(['id' => $id]);
    }

    public static function getFile($id)
    {
        trigger_error("Use ApiStorageFile::viewOne()", E_USER_DEPRECATED);

        return self::get()->setEndpoint('{endpointName}/file-info')->setArgs(['id' => $id]);
    }

    /**
     * Upload file into storage system.
     *
     * Upload example with Yii `UploadedFile`:
     *
     * ```php
     * $file = \yii\web\UploadedFile::getInstance($model, 'ad_image_id');
     * $upload = ApiAdminStorage::fileUpload($file->tempName, $file->type, $file->name)
     *      ->response($client);
     *
     * var_dump($upload->getContent());
     * ```
     *
     * @param string $source The path to the file (typical tmp_name from $_FILES)
     * @param string $type The file mime type (typical type from $_FILES)
     * @param string $name The name of the file (typicali name from $_FILES)
     * @param integer $folderId The folder id, if not given 0 is root directory.
     * @param boolean $isHidden Whether the file should be hidden in admin storage system or not.
     * @return luya\headless\endpoint\PostEndpointRequest
     */
    public static function fileUpload($source, $type, $name, $folderId = 0, $isHidden = true)
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

        return self::post()->setEndpoint('{endpointName}/files-upload')->setArgs($file);
    }

    /**
     * Image Upload
     *
     * Upload example with Yii `UploadedFile`:
     *
     * ```php
     * $file = \yii\web\UploadedFile::getInstance($model, 'ad_image_id');
     * $upload = ApiAdminStorage::imageUpload($file->tempName, $file->type, $file->name)
     *      ->response($client);
     *
     * var_dump($upload->getContent());
     * ```
     *
     * @param string $source The path to the file (typical tmp_name from $_FILES)
     * @param string $type The file mime type (typical type from $_FILES)
     * @param string $name The name of the file (typicali name from $_FILES)
     * @param integer $folderId The folder id, if not given 0 is root directory.
     * @param boolean $isHidden Whether the file should be hidden in admin storage system or not.
     * @return luya\headless\endpoint\PostEndpointRequest
     */
    public static function imageUpload($source, $type, $name, $folderId = 0, $isHidden = true)
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

        return self::post()->setEndpoint('{endpointName}/images-upload')->setArgs($file);
    }
}
