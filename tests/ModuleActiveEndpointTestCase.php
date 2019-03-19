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

    public function getInnerClient(array $data, $status = 200)
    {
        return $this->createDummyClient(Json::encode($data), true, $status);
    }

    protected function getOne()
    {
        $client = $this->getInnerClient($this->getOneResponse(1));
        return $this->getActiveEndpoint()->viewOne(1, $client);
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
        $response = $this->getOne();
        $array = $response->toArray();
        $this->assertSame($this->getOneResponse(1), $array);
    }

    public function testAll()
    {
        $client = $this->getInnerClient($this->getAllResponse());
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
