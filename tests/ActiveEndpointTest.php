<?php

namespace luya\headless\tests;

use luya\headless\tests\HeadlessTestCase;
use luya\headless\ActiveEndpoint;

class ActiveEndpointTest extends HeadlessTestCase
{
    public function testNewModel()
    {
        $model = new TestingActiveEndpoint();
        $model->firstname = 'John';
        $model->lastname = 'Doe';
        
        $this->assertTrue($model->getIsNewRecord());
        $response = $model->save($this->createDummyClient('{"firstname":"J", "lastname": "D"}'));
        $this->assertTrue($response);
        $this->assertSame('John', $model->firstname); // response does not modify the current model value.
        $this->assertTrue($model->getIsNewRecord()); // its also a new model after saving
    }
    
    public function testNewModelWithError()
    {
        $model = new TestingActiveEndpoint();
        $model->firstname = 'John';
        $model->lastname = 'Doe';
        
        $this->assertTrue($model->getIsNewRecord());
        $response = $model->save($this->createDummyClient('[{"field":"firstname", "message": "This name is just hilarious and therefore invalid!"}]', false, 301));
        $this->assertFalse($response);
        $this->assertSame([
            [
            'field' => 'firstname',
            'message' => 'This name is just hilarious and therefore invalid!',
            ]
        ], $model->getErrors()); // response does not modify the current model value.
            
        $this->assertTrue($model->hasError());
        $this->assertTrue($model->getIsNewRecord()); // its also a new model after saving
    }
    
    public function testGetPrimaryKeyValue()
    {
        $model = new TestingActiveEndpoint();
        $model->firstname = 'John';
        $model->lastname = 'Doe';
        
        $this->assertSame('John,Doe', $model->getPrimaryKeyValue());
    }
    
    public function testGetAttributes()
    {
        $model = new TestingActiveEndpoint();
        $this->assertSame(['firstname', 'lastname'], $model->attributes());
    }
    
    public function testUpdateExistingModel()
    {
        $model = TestingActiveEndpoint::findOne(1, $this->createDummyClient('{"firstname":"Baz", "lastname": "Qux"}'));
        
        $this->assertSame('Baz', $model->firstname);
        $this->assertSame('Qux', $model->lastname);
        
        $this->assertFalse($model->getIsNewRecord());
        
        $model->firstname = 'John';
        $model->lastname = 'Doe';
        
        $this->assertTrue($model->save($this->createDummyClient('{}')));
    }
    
    public function testAllWithError()
    {
        $model = TestingActiveEndpoint::find()->all($this->createDummyClient('[]', false));
        
        $this->assertInstanceOf('luya\headless\base\BaseIterator', $model->getModels());
    }
    
    public function testFindOneWithError()
    {
        $model = TestingActiveEndpoint::findOne(1, $this->createDummyClient('[]', false));
        
        $this->assertFalse($model);
    }
    
    public function testFindAllIterator()
    {
        $model = TestingActiveEndpoint::find()->all($this->createDummyClient('[{"firstname":"John", "lastname": "Doe"}]'));
        
        $this->assertSame(1, count($model->getModels()));
        
        foreach ($model->getModels() as $k => $v) {
            $this->assertSame('John,Doe', $k); // composite primary key test
        }
    }
}

class TestingActiveEndpoint extends ActiveEndpoint
{
    public $firstname;
    public $lastname;
    
    public function getPrimaryKeys()
    {
        return ['firstname', 'lastname'];
    }
}