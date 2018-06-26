<?php

namespace luya\headless\cms;

use luya\headless\endpoints\ApiCmsNavitemNavLangItem;
use luya\headless\Client;

/**
 * Get the content of a page.
 */
class Page
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public static function find($client, $langId, $navId)
    {
        return (new static($client))->language($langId)->nav($navId);
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

    public function one()
    {
        return ApiCmsNavitemNavLangItem::find()->setArgs(['langId' => $this->_langId, 'navId' => $this->_navId])->response($this->client)->getContent();
    }
}