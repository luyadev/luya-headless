<?php

namespace luya\headless\tests\modules\admin;

use luya\headless\tests\ModuleActiveEndpointTestCase;

class ApiStorageFileTest extends ModuleActiveEndpointTestCase
{
    public $endpointModel = 'luya\headless\modules\admin\ApiStorageFile';
    
    public function testIsImage()
    {
        $file = $this->getOne();

        $this->assertTrue($file->isImage);

        $fitlerId = 1;
        $image = $file->createImage($fitlerId, $this->getInnerClient([
            'image' => ['id' => 1, 'filter_id' => $fitlerId],
        ]));

        $this->assertNotNull($image);
    }

    public function getOneResponse($id)
    {
        return [
            'id' => $id,
            'is_hidden' => 0,
            'folder_id' => 1,
            'name_original' => null,
            'name_new' => null,
            'name_new_compound' => null,
            'mime_type' => 'image/png',
            'extension' => null,
            'hash_file' => null,
            'hash_name' => null,
            'upload_timestamp' => null,
            'file_size' => null,
            'upload_user_id' => null,
            'is_deleted' => null,
            'passtrough_file' => null,
            'passtrough_file_password' => null,
            'passtrough_file_stats' => null,
            'inline_disposition' => null,
            'source' => 'https://luya.io/storage/xyz.pdf',
            'caption' => null,
            'captions' => null,
            'sizeReadable' => null,
            'isImage' => true,
        ];
    }
}
