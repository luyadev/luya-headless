<?php

namespace luya\headless\modules\admin;

use luya\headless\Client;
use luya\headless\ActiveEndpoint;
use luya\headless\Exception;
use luya\headless\endpoint\ActiveEndpointRequest;

/**
 * Admin Storage Image Model.
 *
 * Expands:
 * + source
 *
 * @property ApiStorageFile $file The file object.
 * @author Basil Suter <basil@nadar.io>
 * @since 1.2.0
 */
class ApiStorageImage extends ActiveEndpoint
{
    public $id;
    public $file_id;
    public $filter_id;
    public $resolution_width;
    public $resolution_height;
    public $source;

    // expand
    public $thumbnail;
    public $tinyCropImage;
    public $mediumThumbnailImage;

    /**
     * @inheritDoc
     */
    public function getEndpointName()
    {
        return '{{%api-admin-storage}}';
    }

    /**
     * @inheritDoc
     */
    public static function find()
    {
        throw new Exception("find() is not supported for images, use ApiStorageFile instead.");
    }

    /**
     * @inheritDoc
     */
    public static function view($id)
    {
        return (new ActiveEndpointRequest(new static))->setEndpoint('{endpointName}/image')->setArgs(['id' => $id]);
    }

    private $_file;

    /**
     * Getter method for file
     *
     * @return ApiStorageFile
     */
    public function getFile()
    {
        return new ApiStorageFile($this->_file);
    }

    /**
     * Setter method for file.
     *
     * @param array $file
     */
    public function setFile($file)
    {
        $this->_file = $file;
    }

    /**
     * Apply the filter for a given image.
     *
     * @param [type] $filterId
     * @param Client $client
     * @return void
     * @since 1.2.0
     */
    public function applyFilter($filterId, Client $client)
    {
        return self::imageFilter($this->file_id, $filterId, $client);
    }

    /**
     * Generate an image filter version.
     *
     * @param integer $fileId
     * @param integer $filterId
     * @param Client $client
     * @return static|boolean The image object or false.
     * @since 1.2.0
     */
    public static function imageFilter($fileId, $filterId, Client $client)
    {
        $response = self::post()
            ->setEndpoint('{endpointName}/image-filter')
            ->setArgs(['fileId' => $fileId, 'filterId' => $filterId])
            ->response($client);

        if (!$response || $response->isError()) {
            return false;
        }

        return new self($response->getContent()['image']);
    }
}
