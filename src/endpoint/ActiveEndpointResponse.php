<?php

namespace luya\headless\endpoint;

use luya\headless\base\BaseIterator;
use luya\headless\base\PaginationInterface;

/**
 * ActiveEndpointResponse contains models getter and pagination informations.
 *
 * The ActiveEndpointResponse object is only accessing the data from {{EndpointResponse}}. Thefore
 * if modification on content or pagination must be done, this can be done in {{EndpointResponse}}.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class ActiveEndpointResponse implements PaginationInterface
{
    /**
     * @var EndpointResponse
     */
    public $response;
    
    /**
     * @var \luya\headless\base\BaseIterator
     */
    public $models;
    
    /**
     * Create new ActiveEndpointResponse
     *
     * @param EndpointResponse $response
     * @param BaseIterator $models
     */
    public function __construct(EndpointResponse $response, BaseIterator $models)
    {
        $this->response = $response;
        $this->models = $models;
    }
    
    /**
     * @return \luya\headless\base\BaseIterator
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * Returns the json parsed content as array.
     *
     * @return array Returns the parsed json content as array.
     */
    public function getContent()
    {
        return $this->response->getContent();
    }
    
    // PaginationInterface
    
    /**
     * @inheritdoc
     */
    public function getTotalCount()
    {
        return $this->response->getTotalCount();
    }
    
    /**
     * @inheritdoc
     */
    public function getPageCount()
    {
        return $this->response->getPageCount();
    }
    
    /**
     * @inheritdoc
     */
    public function getCurrentPage()
    {
        return $this->response->getCurrentPage();
    }
    
    /**
     * @inheritdoc
     */
    public function getPerPage()
    {
        return $this->response->getPerPage();
    }
    
    /**
     * @inheritdoc
     */
    public function isLastPage()
    {
        return $this->response->isLastPage();
    }
    
    /**
     * @inheritdoc
     */
    public function isFirstPage()
    {
        return $this->response->isFirstPage();
    }
    
    /**
     * @inheritdoc
     */
    public function getNextPageId()
    {
        return $this->response->getNextPageId();
    }
    
    /**
     * @inheritdoc
     */
    public function getPreviousPageId()
    {
        return $this->response->getPreviousPageId();
    }
}
