<?php

namespace luya\headless\endpoint;

use luya\headless\Client;
use luya\headless\base\AbstractRequestClient;
use luya\headless\base\AbstractEndpointRequest;

/**
 * EndpointRequest represents a request to a class with a response object in response().
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class PutEndpointRequest extends AbstractEndpointRequest
{
    /**
     * Response trough get request.
     *
     * {@inheritDoc}
     * @see \luya\headless\base\AbstractEndpointRequest::createResponse()
     */
    public function createResponse(AbstractRequestClient $request)
    {
        return new EndpointResponse($request->put($this->getArgs() ?: []), $this);
    }
}
