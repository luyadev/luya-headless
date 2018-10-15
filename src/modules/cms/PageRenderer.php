<?php

namespace luya\headless\modules\cms;

use luya\headless\modules\cms\models\NavItemPage;
use luya\headless\modules\cms\models\NavItemPageRowCol;
use luya\headless\modules\cms\models\NavItemPageBlock;
use luya\headless\Exception;

/**
 * Render a given Page.
 *
 * The page renderer helps you to render a given page by providing recursive blocks
 * and provide an option to register the block views.
 *
 * An example usgae for rendering the current page version:
 *
 * ```php
 * $pageResponse = Page::find(1, 1)->response($client);
 * $renderer = new PageRenderer($pageResponse->getCurrentPageVersion());
 *
 * // assign the block views
 * HeadingBlock::register(4, $renderer);
 * TextBlock::register(6, $renderer);
 * LayoutBlock::register(11, $renderer);
 *
 * // render the page
 * echo $renderer->render();
 * ```
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class PageRenderer
{
    /**
     * @var NavItemPage
     */
    public $navItemPage;

    /**
     *
     * @param NavItemPage $navItemPage
     */
    public function __construct(NavItemPage $navItemPage)
    {
        $this->navItemPage = $navItemPage;
    }

    private $_blockViews = [];

    /**
     * Map a given block view to an ID
     *
     * @param integer $identifier Can be either block_id, block_class or block_class_name.
     * @param string $blockViewObject The class object path like `path\to\psr4\namespace\BlockView`
     */
    public function setBlockView($identifier, $blockViewObject)
    {
        $this->_blockViews[$identifier] = $blockViewObject;
    }

    /**
     *
     * @param integer $id
     * @param NavItemPageBlock $block
     * @throws Exception
     * @return string
     */
    public function renderBlock(NavItemPageBlock $block)
    {
        if (isset($this->_blockViews[$block->block_id])) {
            return $this->renderBlockView($this->_blockViews[$block->block_id], $block);
        } elseif (isset($this->_blockViews[$block->block_class])) {
            return $this->renderBlockView($this->_blockViews[$block->block_class], $block);
        } elseif (isset($this->_blockViews[$block->block_class_name])) {
            return $this->renderBlockView($this->_blockViews[$block->block_class_name], $block);
        }

        throw new Exception(sprintf('Unable to findet a registered block view for id "%s", class "%s" or class name "%s"', $block->block_id, $block->block_class, $block->block_class_name));
    }

    private function renderBlockView($className, NavItemPageBlock $block)
    {
        $view = new $className($block, $this);
        return $view->render();
    }

    /**
     * render the current page blocks including rows and cols.
     *
     * @return string
     */
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

    /**
     * Render the blocks for a given col.
     *
     * @param NavItemPageRowCol $col
     * @return string
     */
    public function blockRendering(NavItemPageRowCol $col)
    {
        $c = null;
        foreach ($col->getBlocks() as $block) {
            $c.= $this->renderBlock($block);
        }

        return $c;
    }
}
