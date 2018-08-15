<?php

namespace luya\headless\base;

use luya\headless\ActiveEndpoint;
use luya\headless\Exception;

/**
 * Generate an iterator object for a given model an array with data.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class BaseIterator implements \Iterator, \Countable
{
    /**
     * @var string The full qualified class name for the given model to create an iteration.
     */
    protected $modelClass;
    
    /**
     * @var boolean Whether the current row item is a new record or not. 
     */
    protected $isNewRecord = true;
    
    /**
     * @var array Holds the array data for the Iterator interface.
     */
    protected $data = [];
    
    /**
     * @param string $modelClass The model class to create for the item.
     * @param array $items Iterate over the array with the items and generate an object for every entry.
     * @param string|array $keyColumn The column from the items array which should be token to generate the index column value.
     */
    public static function create($modelClass, array $items, $keyColumn = null, $isNewRecord = null)
    {
        $object = new self();
        $object->modelClass = $modelClass;
        $object->isNewRecord = $isNewRecord;
        $object->addItems($items, $keyColumn);
        return $object;
    }
    
    /**
     * 
     * @param array $items
     * @param string|array $keyColumn
     */
    public function addItems(array $items, $keyColumn)
    {
        foreach ($items as $key => $item) {

            if (!is_array($item)) {
                throw new Exception(sprintf('The given iterator item must be type of array in order to assign them to "%s" model.', $this->modelClass));
            }

            $pkValues = [];
            foreach ((array) $keyColumn as $columnValue) {
                // check if the column exists in the current query
                if (isset($item[$columnValue])) {
                    $pkValues[] = $item[$columnValue];
                }
            }
            
            // if $pkValues is empty it means it could be that the primary key fields is not available in the current fields list (setFields()).
            // therefore we just use the key
            $this->addItem($item, (is_null($keyColumn) || empty($pkValues)) ? $key : implode(",", $pkValues));
        }
    }
    
    /**
     * Add new item to array of items.
     * 
     * @param array $item The array with key value pairing where key is the attribute name.
     * @param string $key The value which is used for the indexing of the data array.
     */
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