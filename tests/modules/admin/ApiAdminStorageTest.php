<?php

namespace luya\headless\tests\modules\admin;

use luya\headless\tests\ModuleActiveEndpointTestCase;
use luya\headless\tests\HeadlessTestCase;
use luya\headless\modules\admin\ApiAdminStorage;

class ApiAdminStorageTest extends HeadlessTestCase
{
    public function testImageUpload()
    {
        $image = __DIR__ . '/../../data/image.png';
        $client = $this->createDummyClient([
            'image' => [
                'id' => 1,
                'filter_id' => 0,
            ],
            'tinyCrop' => [

            ],
            'mediumThumbnail' => [

            ]
        ]);
        $upload = ApiAdminStorage::imageUpload($client, $image, 'image/jpg', 'image.jpg');

        $this->assertInstanceOf('luya\headless\modules\admin\ApiStorageImage', $upload);
    }

    public function testFileUpload()
    {
        $image = __DIR__ . '/../../data/image.png';
        $client = $this->createDummyClient([
            'file' => [
                'id' => 1,
                'source' => 'path/to/image/on/server/png',
            ],
        ]);
        $upload = ApiAdminStorage::fileUpload($client, $image, 'image/jpg', 'image.jpg');

        $this->assertInstanceOf('luya\headless\modules\admin\ApiStorageFile', $upload);
    }
}
