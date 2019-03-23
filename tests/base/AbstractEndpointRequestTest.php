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

    public function testOverrideEndpointName()
    {
        $endpoint = new MyTestEndpoint();
        $request = new MyEndpointRequest($endpoint);

        $this->assertSame('foobar', $request->getEndpoint());

        $request->setTokens(['endpointName' => 'barfoo']);
        $this->assertSame('barfoo', $request->getEndpoint());

        $request->setTokens(['endpointName' => '{{%xyz}}']);
        $this->assertSame('{{%xyz}}', $request->getEndpoint());
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

    public function testExpandDefault()
    {
        $endpoint = new MyTestEndpoint();
        $request = new MyEndpointRequest($endpoint);
        $request->setDefaultExpand(['foo', 'bar']);

        $this->assertSame([
            'expand' => 'foo,bar',
        ], $request->getArgs());

        $request = new MyEndpointRequest($endpoint);
        $request->setDefaultExpand(['foo', 'bar']);
        $request->setExpand(['john', 'doe']);
        $this->assertSame([
            'expand' => 'foo,bar,john,doe',
        ], $request->getArgs());
        
        $request = new MyEndpointRequest($endpoint);
        $request->setDefaultExpand(['foo', 'bar']);
        $request->setExpand([]);
        $this->assertSame([
            'expand' => 'foo,bar',
        ], $request->getArgs());

        $request = new MyEndpointRequest($endpoint);
        $request->setExpand(['test']); // this won't have any effect as it must be defined after set default expand.
        $request->setDefaultExpand(['foo', 'bar']);
        $this->assertSame([
            'expand' => 'foo,bar',
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
