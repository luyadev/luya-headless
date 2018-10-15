<?php

namespace luya\headless\tests;

use luya\helpers\Json;
use yii\base\InvalidConfigException;

abstract class ModuleActiveEndpointTestCase extends HeadlessTestCase
{
    public $endpointModel;

    abstract public function getOneResponse($id);

    public function getAllResponse()
    {
        return [
            $this->getOneResponse(1),
            $this->getOneResponse(2),
        ];
    }
    /**
     *
     *
     * @return \luya\headless\ActiveEndpoint
     */
    private function getActiveEndpoint()
    {
        if (empty($this->endpointModel)) {
            throw new InvalidConfigException("The endpointModel property can not be empty.");
        }
        $class = $this->endpointModel;
        return new $class;
    }

    public function testOne()
    {
        $client = $this->createDummyClient(Json::encode($this->getOneResponse(1)), true, 200);
        $response = $this->getActiveEndpoint()->viewOne(1, $client);
        $array = $response->toArray();
        $this->assertSame($this->getOneResponse(1), $array);
    }

    public function testAll()
    {
        $client = $this->createDummyClient(Json::encode($this->getAllResponse()), true, 200);
        $response = $this->getActiveEndpoint()->findAll($client);
        $array = $response->getContent();
        $this->assertSame($this->getAllResponse(), $array);

        $mm = [];
        foreach ($response->getModels() as $model) {
            $mm[] = $model->toArray();
        }

        $this->assertSame($this->getAllResponse(), $mm);
    }
}
