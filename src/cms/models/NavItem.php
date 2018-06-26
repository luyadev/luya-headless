<?php

namespace luya\headless\cms\models;

use luya\headless\base\BaseModel;

class NavItem extends BaseModel
{
    public $id;
    public $nav_id;
    public $lang_id;
    public $nav_item_type;
    public $nav_item_type_id;
    public $create_user_id;
    public $update_user_id;
    public $timestamp_create;
    public $timestamp_update;
    public $title;
    public $alias;
    public $description;
    public $keywords;
    public $title_tag;
}