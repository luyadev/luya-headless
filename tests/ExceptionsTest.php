<?php

namespace luya\headless\tests;

use luya\headless\Exception;

class ExceptionsTest extends HeadlessTestCase
{
    public function testEmptyException()
    {
        $exception = new Exception('message');
        $this->assertSame('message', $exception->getMessage());
    }
}
