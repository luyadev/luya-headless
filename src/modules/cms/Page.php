<?php

namespace luya\headless\modules\cms;

use luya\headless\Client;

/**
 * Get the content of a page.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Page
{
    /**
     * @return self
     */
    public static function find($langId, $navId)
    {
        return (new static())->language($langId)->nav($navId);
    }

    private $_langId;
    
    public function language($langId)
    {
        $this->_langId = $langId;
        return $this;
    }

    private $_navId;

    public function nav($navId)
    {
        $this->_navId = $navId;
        return $this;
    }

    /**
     *
     * @param Client $client
     * @return PageResponse
     */
    public function response(Client $client)
    {
        $response = ApiCmsNavitemNavLangItem::index()->setArgs(['langId' => $this->_langId, 'navId' => $this->_navId])->response($client)->getContent();
        
        return new PageResponse($response);
    }
}
