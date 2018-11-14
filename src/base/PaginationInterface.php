<?php

namespace luya\headless\base;

/**
 * Pagination Interface.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
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
