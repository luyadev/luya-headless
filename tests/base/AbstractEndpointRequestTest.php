<?php

namespace luya\headless\tests\base;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\base\EndpointInterface;
use luya\headless\base\AbstractEndpointRequest;

class AbstractEndpointRequestTest extends HeadlessTestCase
{
    public function testRequiredArguments()
    {
        $endpoint = new MyTestEndpoint();
        $request = new MyEndpointRequest($endpoint);
        $request->setRequiredArgs(['foo' => 'bar']);
        $this->expectException('luya\headless\exceptions\MissingArgumentsException');
        $request->response($this->getClient());
    }

    public function testExpandFieldsSortFilter()
    {
        $endpoint = new MyTestEndpoint();
        $request = new MyEndpointRequest($endpoint);
        $request->setExpand(['users', 'news']);
        $request->setFields(['firstname', 'lastname']);
        $request->setSort(['firstname' => SORT_ASC, 'lastname' => SORT_DESC]);
        $request->setFilter(['is_deleted' => 1]);

        $this->assertSame([
            'expand' => 'users,news',
            'fields' => 'firstname,lastname',
            'sort' => 'firstname,-lastname',
            'filter' => [
                'is_deleted' => 1,
            ]
        ], $request->getArgs());
    }
}

class MyEndpointRequest extends AbstractEndpointRequest
{
    public function createResponse(\luya\headless\base\AbstractRequestClient $request)
    {
        return 'foo';
    }
}

class MyTestEndpoint implements EndpointInterface
{
    public function getEndpointName()
    {
        return 'foobar';
    }
    
    public static function get()
    {
        return 'get';
    }
    
    public static function post()
    {
        return 'post';
    }
    
    public static function put()
    {
        return 'put';
    }
    
    public static function delete()
    {
        return 'delete';
    }
}
