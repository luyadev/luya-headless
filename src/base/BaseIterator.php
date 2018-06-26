<?php

namespace luya\headless\base;

class BaseIterator implements \Iterator, \Countable
{
    public $modelClass;
    
    protected $data = [];
    
    public static function create($modelClass, array $items, $keyColumn)
    {
        $object = new self();
        $object->modelClass = $modelClass;
        $object->addItems($items, $keyColumn);
        return $object;
    }
    
    public function addItems(array $items, $keyColumn)
    {
        foreach ($items as $item) {
            $this->addItem($item, $item[$keyColumn]);
        }
    }
    
    public function addItem(array $item, $key)
    {
        $class = $this->modelClass;
        
        $model = new $class($item);
        
        $this->data[$key] = $model;
    }
    
    public function count()
    {
        return count($this->data);
    }
    
    public function rewind()
    {
        return reset($this->data);
    }
    
    public function current()
    {
        return current($this->data);
    }
    
    public function key()
    {
        return key($this->data);
    }
    
    public function next()
    {
        return next($this->data);
    }
    
    public function valid()
    {
        return key($this->data) !== null;
    }
}