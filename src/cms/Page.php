<?php

namespace luya\headless\cms;

use luya\headless\endpoints\ApiCmsNavitemNavLangItem;
use luya\headless\Client;

/**
 * Get the content of a page.
 */
class Page
{
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
     * @return \luya\headless\cms\PageResponse
     */
    public function response(Client $client)
    {
        $response = ApiCmsNavitemNavLangItem::find()->setArgs(['langId' => $this->_langId, 'navId' => $this->_navId])->response($client)->getContent();
        
        return new PageResponse($response);
    }
}