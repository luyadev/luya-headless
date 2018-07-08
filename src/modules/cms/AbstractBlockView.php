<?php

namespace luya\headless\modules\cms;

use \luya\headless\modules\cms\models\NavItemPageBlock;

abstract class AbstractBlockView
{
    public abstract function render();
    
    public $block;
    public $renderer;
    public function __construct(NavItemPageBlock $block, PageRenderer $renderer)
    {
        $this->block = $block;
        $this->renderer = $renderer;
    }

    public static function register($id, PageRenderer $rendere)
    {
        $rendere->setBlockView($id, get_called_class());
    }

    public function varValue($varName, $defaultValue = null)
    {
        return $this->block->getVarValue($varName) ?: $defaultValue;
    }

    public function cfgValue($cfgName, $defaultValue = null)
    {

    }

    public function getPlaceholder($name)
    {
        $c = null;

        foreach ($this->block->getPlaceholder($name)->getBlocks() as $block) {
            $c.= $this->renderer->renderBlock($block->block_id, $block);
        }

        return $c;
    }

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