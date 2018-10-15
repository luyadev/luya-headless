<?php

namespace luya\headless\base;

interface PaginationInterface
{
    public function getTotalCount();
    
    public function getPageCount();
    
    public function getCurrentPage();
    
    public function getPerPage();
    
    public function isLastPage();
    
    public function isFirstPage();
    
    public function getNextPageId();
    
    public function getPreviousPageId();
}
