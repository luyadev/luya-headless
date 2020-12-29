<?php

namespace luya\headless\endpoint;

use luya\headless\base\BaseIterator;
use luya\headless\Client;
use luya\headless\base\AbstractRequestClient;
use luya\headless\base\AbstractEndpointRequest;

/**
 * Active Endpoint Request.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ActiveEndpointRequest extends AbstractEndpointRequest
{
    private $_indexBy;

    /**
     * Index by Colum Name
     * 
     * By default the data will be indexed by the {{luya\headless\ActiveEndpoint::getPrimaryKeys()}}. In order to index by another
     * column its possible to provide `indexBy(attributeName)` where attributeName is a propertie inside the requested ActiveEndpoint Model.
     *
     * @param string $attributeName The attribute name which should be taken as index (assoc key) for the array.
     * @return static
     * @since 2.7.0
     */
    public function indexBy($attributeName)
    {
        $this->_indexBy = $attributeName;
        return $this;
    }
    
    /**
     * Response trough get request.
     *
     * {@inheritDoc}
     * @see \luya\headless\base\AbstractEndpointRequest::createResponse()
     */
    public function createResponse(AbstractRequestClient $request)
    {
        return new EndpointResponse($request->get($this->getArgs() ?: []), $this);
    }
    
    /**
     * Iterates over the current response content and assignes every item to the active endpoint model.
     *
     * ```php
     * $models = APi::find()->all($client);
     * ```
     *
     * @param Client $client
     * @return \luya\headless\endpoint\ActiveEndpointResponse
     */
    public function all(Client $client)
    {
        $response = $this->response($client);
        $models = $response->getContent();
        
        if (empty($models)) {
            $models = [];
        }
        
        $models = BaseIterator::create(get_class($this->getEndpointObject()), $models, $this->_indexBy ? $this->_indexBy : $response->endpoint->getPrimaryKeys(), false);
        
        return new ActiveEndpointResponse($response, $models);
    }

    /**
     * Iteratrs trough all pages and returns an arry with the models.
     *
     * @param Client $client
     * @param integer $perPage The amount of items which can be loaded per requests, this is commonly limited on the api side. If you are
     * aware of the max limit this value can be increased.
     * @return static
     */
    public function allPages(Client $client, $perPage = 50)
    {
        $data = [];
        $first = $this->setPerPage($perPage)->all($client);
        foreach ($first->getModels() as $key => $model) {
            $data[$key] = $model;
        }
        
        $start = $first->getCurrentPage() + 1;
        for ($i=$start; $i<=$first->getPageCount(); $i++) {
            $find = $this->setPage($i)->setPerPage($perPage)->all($client);
            foreach ($find->getModels() as $key => $model) {
                $data[$key] = $model;
            }
        }
        
        return $data;
    }

    /**
     * Takes the first row from an an array into an active endpoint model.
     *
     * This is comonoly used when retrieving data from find but return only the first record.
     *
     * ```php
     * $model = Api::find()->setPerPage(1)->first($client);
     * ```
     *
     * @param Client $client
     * @since 1.0.0
     * @return static
     */
    public function first(Client $client)
    {
        $response = $this->response($client);
        $content = $this->responseToContent($response);

        if (!$content) {
            return false;
        }

        return $this->contentToModel(current($content));
    }
    
    /**
     * Takes the current response content into the active endpoint model.
     *
     * ```php
     * $model = Api::view($id)->setExpand(['images'])->one($client);
     * ```
     *
     * @param Client $client
     * @return static
     */
    public function one(Client $client)
    {
        $response = $this->response($client);
        $content = $this->responseToContent($response);

        if (!$content) {
            return false;
        }
        
        return $this->contentToModel($content);
    }

    /**
     * Parse the response object into content, if empty or response is error return false.
     *
     * @param EndpointResponse $response
     * @return mixed
     */
    private function responseToContent(EndpointResponse $response)
    {
        // the one api returns an error, therefore we do not handle this error but return false instead, this is the expected
        // behavior when using one() inside your application, even when the api returns a 404.
        if ($response->isError()) {
            return false;
        }

        $content = $response->getContent();

        if (empty($content)) {
            return false;
        }

        return $content;
    }

    /**
     * Create new model for the current request with the given content.
     *
     * @param mixed $content
     * @return \luya\headless\ActiveEndpoint
     */
    private function contentToModel($content)
    {
        $className = get_class($this->endpointObject);
        $model = new $className($content);
        $model->isNewRecord = false;

        return $model;
    }
}
