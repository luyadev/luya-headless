<?php

namespace luya\headless\modules\cms\models;

use luya\headless\base\BaseModel;

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