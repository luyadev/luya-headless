<?php

namespace luya\headless\tests;

use luya\testsuite\cases\WebApplicationTestCase;

class HeadlessTestCase extends WebApplicationTestCase
{
    public function getConfigArray()
    {
        return [
           'id' => 'mytestapp',
           'basePath' => dirname(__DIR__),
        ];
    }
}