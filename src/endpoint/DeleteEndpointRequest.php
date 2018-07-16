<?php

namespace luya\headless\endpoint;

use luya\headless\base\AbstractRequest;

/**
 * EndpointRequest represents a request to a class with a response object in response().
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class DeleteEndpointRequest extends AbstractEndpointRequest
{
    /**
     * Response trough delete request.
     *
     * {@inheritDoc}
     * @see \luya\headless\endpoint\AbstractEndpointRequest::createResponse()
     */
    public function createResponse(AbstractRequest $request)
    {
        return (new EndpointResponse($request->delete($this->getArgs() ?: []), $this->endpointObject));
    }
}