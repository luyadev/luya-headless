<?php

namespace luya\headless\modules\cms;

use \luya\headless\modules\cms\models\NavItemPageBlock;

/**
 * Abstract class for Block Views.
 *
 * This class represents a view for a given block. You can access all the block related
 * context informations in order to render those blocks nicely.
 *
 * A very basic example for a text block, assmuing the the var with the content of the block
 * is located in `content`:
 *
 * ```php
 * class TextBlock extends \luya\headless\modules\cms\AbstractBlockView
 * {
 *     public function render()
 *     {
 *        return '<p>' . $this->varValue('content') . '</p>';
 *     }
 * }
 * ```
 *
 * An example with a layout block which have two placeholders `left` and `right` which
 * can contain block recursions:
 *
 * ```php
 * class LayoutBlock extends \luya\headless\modules\cms\AbstractBlockView
 * {
 *     public function render()
 *     {
 *         return '<div class="row"><div class="col-md-6">' . $this->getPlaceholder('left') . '</div><div class="col-md-6">'. $this->getPlaceholder('right') . '</div></div>';
 *
 *     }
 * }
 * ```
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
abstract class AbstractBlockView
{
    /**
     * The render method must return a string with the rendered block content.
     */
    abstract public function render();
    
    /**
     * @var NavItemPageBlock
     */
    public $block;
    
    /**
     * @var PageRenderer
     */
    public $renderer;
    
    /**
     *
     * @param NavItemPageBlock $block
     * @param PageRenderer $renderer
     */
    public function __construct(NavItemPageBlock $block, PageRenderer $renderer)
    {
        $this->block = $block;
        $this->renderer = $renderer;
    }

    /**
     * Register the block into the renderer.
     *
     * @param integer $identifier The identifier can be either block_id, block_class or block_class_name
     * @param PageRenderer $rendere
     */
    public static function register($identifier, PageRenderer $renderer)
    {
        $renderer->setBlockView($identifier, get_called_class());
    }

    /**
     *
     * @param string $varName
     * @param mixed $defaultValue
     * @return string
     */
    public function varValue($varName, $defaultValue = null)
    {
        return $this->block->getVarValue($varName) ?: $defaultValue;
    }

    /**
     *
     * @param string $cfgName
     * @param string $defaultValue
     * @return string
     */
    public function cfgValue($cfgName, $defaultValue = null)
    {
        return $this->block->getCfgValue($varName) ?: $defaultValue;
    }

    /**
     *
     * @param string $extraName
     * @param string $defaultValue
     * @return string
     */
    public function extraValue($extraName, $defaultValue = null)
    {
        return $this->block->getExtraValue($extraName) ?: $defaultValue;
    }
    
    /**
     * Get the rendered content of a given placeholder.
     *
     * @param string $name
     * @return string
     */
    public function getPlaceholder($name)
    {
        $c = null;

        foreach ($this->block->getPlaceholder($name)->getBlocks() as $block) {
            $c.= $this->renderer->renderBlock($block);
        }

        return $c;
    }

    /**
     * Get an array with all rows in order to perform the rows, cols, blocks while.
     *
     * @return \luya\headless\modules\cms\models\NavItemPageRow
     */
    public function getPlaceholderRows()
    {
        return $this->block->getRows();
    }

    /*
    protected function renderFile($_file_, array $_params_ = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            require $_file_;
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }

    }
    */
}
