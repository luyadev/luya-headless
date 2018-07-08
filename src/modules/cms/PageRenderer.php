<?php

namespace luya\headless\modules\cms;

use luya\headless\modules\cms\models\NavItemPage;
use luya\headless\modules\cms\models\NavItemPageRowCol;
use luya\headless\modules\cms\models\NavItemPageBlock;
use luya\headless\Exception;

class PageRenderer
{
    public $navItemPage;

    public function __construct(NavItemPage $navItemPage)
    {
        $this->navItemPage = $navItemPage;
    }

    private $_blockViews = [];

    public function setBlockView($id, $blockViewObject)
    {

        $this->_blockViews[$id] = $blockViewObject;
    }

    public function renderBlock($id, NavItemPageBlock $block)
    {
        if (!isset($this->_blockViews[$id])) {
            throw new Exception("Unable to find the given block view for id $id.");
        }
        $className = $this->_blockViews[$id];
        
        $view = new $className($block, $this);
        return $view->render();
    }

    public function render()
    {
        $c = null;

        foreach ($this->navItemPage->getRows() as $row) {
            $c.= '<div class="row">';
                foreach ($row->getCols() as $col) {
                    $c.= '<div class="col-md-'.$col->getSize().'">';
                        $c.= $this->blockRendering($col);
                    $c.= '</div>';
                }
            $c.= '</div>';
        }

        return $c;
    }

    public function blockRendering(NavItemPageRowCol $col)
    {
        $c = null;
        foreach ($col->getBlocks() as $block) {
            $c.= $this->recursiveBlocks($block);
        }

        return $c;
    }

    public function recursiveBlocks(NavItemPageBlock $block)
    {
        return $this->renderBlock($block->block_id, $block);
    }
}