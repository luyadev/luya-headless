<?php

namespace luya\headless\tests\cms;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\cms\Page;

class PageTest extends HeadlessTestCase
{
    public function testGetPageContent()
    {
        $client = $this->createDummyClient('{"item":{"id":1},"nav":{"id":1},"error": 0}');

        $page = Page::find(1,1)->response($client);

        $this->assertSame(1, $page->item->id);
        $this->assertSame(1, $page->nav->id);
        $this->assertSame(0, $page->error);
    }
}