<?php

namespace luya\headless\base;

use luya\headless\ActiveEndpoint;

/**
 * Generate an iterator object for a given model an array with data.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class BaseIterator implements \Iterator, \Countable
{
    protected $modelClass;
    
    protected $isNewRecord = true;
    
    protected $data = [];
    
    /**
     * @param string $modelClass The model class to create for the item.
     * @param array $items Iterate over the array with the items and generate an object for every entry.
     * @param string $keyColumn The column from the items array which should be token to generate the index column value.
     */
    public static function create($modelClass, array $items, $keyColumn = null, $isNewRecord = null)
    {
        $object = new self();
        $object->modelClass = $modelClass;
        $object->isNewRecord = $isNewRecord;
        $object->addItems($items, $keyColumn);
        return $object;
    }
    
    public function addItems(array $items, $keyColumn)
    {
        foreach ($items as $key => $item) {
            $pkValues = [];
            foreach ((array) $keyColumn as $columnValue) {
                $pkValues[] = $item[$columnValue];
            }
            $this->addItem($item, is_null($keyColumn) ? $key : implode(",", $pkValues));
        }
    }
    
    public function addItem(array $item, $key)
    {
        $class = $this->modelClass;
        
        $model = new $class($item);
        
        // @TODO not sure if this should be part of every BaseModel or only AbstractActiveEndpoint
        if ($model instanceof ActiveEndpoint) {
            $model->isNewRecord = $this->isNewRecord;
        }
        
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