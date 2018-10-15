<?php

namespace luya\headless\modules\cms\models;

use luya\headless\base\BaseModel;

/**
 * Represents a data instance from a Block.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class NavItemPageBlock extends BaseModel
{
    public $id;
    public $is_dirty;
    public $is_container;
    public $block_id;
    public $is_hidden;
    public $name;
    public $icon;
    public $full_name;
    public $twig_admin;
    public $vars;
    public $cfgs;
    public $extras;
    public $values;
    public $field_help;
    public $cfgvalues;
    public $__placeholders;
    public $variations;
    public $variation;
    public $is_dirty_dialog_enabled;
    public $block_class_name;
    public $block_class;

    /**
     * Get the content for a given var.
     *
     * @param string $varName
     * @return mixed|boolean
     */
    public function getVarValue($varName)
    {
        return isset($this->values[$varName]) ? $this->values[$varName] : false;
    }

    /**
     * Get the content for a given cfg.
     *
     * @param string $varName
     * @return mixed|boolean
     */
    public function getCfgValue($varName)
    {
        return isset($this->cfgs[$varName]) ? $this->cfgs[$varName] : false;
    }
    
    /**
     * Get the content for a given extra.
     *
     * @param string $varName
     * @return mixed|boolean
     */
    public function getExtraValue($varName)
    {
        return isset($this->extras[$varName]) ? $this->extras[$varName] : false;
    }
    
    /**
     * @return NavItemPageRow
     */
    public function getRows()
    {
        $models = [];
        foreach ($this->__placeholders as $rowId => $cols) {
            $models[] = new NavItemPageRow(['index' => $rowId, 'cols' => $cols]);
        }
        
        return $models;
    }

    /**
     *
     * @param string $name The name of the placeholder, like `left`, `sidebar` etc.
     * @return NavItemPageBlockPlaceholder
     */
    public function getPlaceholder($name)
    {
        foreach ($this->__placeholders as $cols) {
            foreach ($cols as $col) {
                if ($col['var'] == $name) {
                    return new NavItemPageBlockPlaceholder($col);
                }
            }
        }
    }
    
    /*
     *
     * 'is_dirty' => true
                        'is_container' => 0
                        'id' => '9'
                        'block_id' => '8'
                        'is_hidden' => '0'
                        'name' => 'Heading'
                        'icon' => 'format_size'
                        'full_name' => '<i class=\"material-icons\">format_size</i> <span>Heading</span>'
                        'twig_admin' => '{% if vars.content is not empty %}<{{vars.headingType}}>{{ vars.content }}</{{vars.headingType}}>{% else %}<span class=\"block__empty-text\">No heading data has been provided yet.</span>{% endif %}'
                        'vars' => [...]
                        'cfgs' => [...]
                        'extras' => [...]
                        'values' => [...]
                        'field_help' => [...]
                        'cfgvalues' => [...]
                        '__placeholders' => [...]
                        'variations' => false
                        'variation' => '0'
                        'is_dirty_dialog_enabled' => true
     */
}
