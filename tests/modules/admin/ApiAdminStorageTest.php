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

        $upload = ApiAdminStorage::imageUpload($image, 'image/jpg', 'image.jpg')->response($this->createDummyClient([
            'image' => [
                'id' => 1,
                'filter_id' => 0,
            ],
            'tinyCrop' => [

            ],
            'mediumThumbnail' => [

            ]
        ]));

        $this->assertNotNull($upload->getContent());
    }

    public function testFileUpload()
    {
        $image = __DIR__ . '/../../data/image.png';

        $upload = ApiAdminStorage::fileUpload($image, 'image/jpg', 'image.jpg')->response($this->createDummyClient([
            'file' => [
                'id' => 1,
                'source' => 'path/to/image/on/server/png',
            ],
        ]));

        $this->assertNotNull($upload->getContent());
    }
}
