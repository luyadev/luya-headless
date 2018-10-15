<?php

namespace luya\headless\endpoint;

;

use luya\headless\base\AbstractRequestClient;
use luya\headless\base\PaginationInterface;
use luya\headless\base\EndpointInterface;
use luya\headless\base\AbstractEndpointRequest;

/**
 * EndpointResponse represents a response object from the AbstractRequestClient class
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class EndpointResponse implements PaginationInterface
{
    /**
     * @var \luya\headless\base\AbstractRequestClient
     */
    public $requestClient;
    
    /**
     * @var EndpointInterface
     */
    public $endpoint;

    public $request;
    
    /**
     * Create new endpoint response from an {{AbstratRequest}}.
     */
    public function __construct(AbstractRequestClient $requestClient, AbstractEndpointRequest $request)
    {
        $this->requestClient = $requestClient;
        $this->request = $request;
        $this->endpoint = $request->getEndpointObject();
    }
    
    public function getTotalCount()
    {
        return (int) $this->requestClient->getResponseHeader('X-Pagination-Total-Count');
    }
    
    public function getPageCount()
    {
        return (int) $this->requestClient->getResponseHeader('X-Pagination-Page-Count');
    }
    
    public function getCurrentPage()
    {
        return (int) $this->requestClient->getResponseHeader('X-Pagination-Current-Page');
    }
    
    public function getPerPage()
    {
        return (int) $this->requestClient->getResponseHeader('X-Pagination-Per-Page');
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
        return $this->request->callContentProcessor($this->requestClient->getParsedResponse());
    }
    
    public function isSuccess()
    {
        return $this->requestClient->isSuccess();
    }
    
    public function isError()
    {
        return !$this->isSuccess();
    }
    
    public function getStatusCode()
    {
        return $this->requestClient->getResponseStatusCode();
    }
}
