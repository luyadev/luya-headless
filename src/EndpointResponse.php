<?php

namespace luya\headless;

use luya\headless\base\AbstractRequest;

/**
 * EndpointResponse represents a response object from the AbstractRequest class
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class EndpointResponse
{
    public $request;
    
    /**
     * Create new endpoint response from an {{AbstratRequest}}.
     */
    public function __construct(AbstractRequest $request)
    {
        $this->request = $request;
    }
    
    public function getTotalCount()
    {
        return $this->request->getResponseHeader('X-Pagination-Total-Count');
    }
    
    public function getPageCount()
    {
        return $this->request->getResponseHeader('X-Pagination-Page-Count');
    }
    
    public function getCurrentPage()
    {
        return $this->request->getResponseHeader('X-Pagination-Current-Page');
    }
    
    public function getPerPage()
    {
        return $this->request->getResponseHeader('X-Pagination-Per-Page');
    }
    
    public function isLastPage()
    {
        return $this->getPageCount() == $this->getCurrentPage();
    }
    
    public function isFirstPage()
    {
        return $this->getCurrentPage() == 1;
    }
    
    public function getNextPageId()
    {
        return $this->isLastPage() ? $this->getCurrentPage() : $this->getCurrentPage() + 1;
    }
    
    public function getPreviousPageId()
    {
        return $this->isFirstPage() ? $this->getCurrentPage() : $this->getCurrentPage() -1;
    }
    
    /**
     * Returns the json parsed content as array.
     * 
     * @return array Returns the parsed json content as array.
     */
    public function getContent()
    {
        return $this->request->getParsedResponse();
    }
    
    public function isSuccess()
    {
        return $this->request->isSuccess();
    }
    
    public function isError()
    {
        return !$this->isSuccess();
    }
    
    public function getStatusCode()
    {
        return $this->request->getResponseStatusCode();
    }
}