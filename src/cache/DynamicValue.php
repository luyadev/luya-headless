<?php

namespace luya\headless\cache;

/**
 * Generate Dynamic Value expression.
 * 
 * When cache keys are generated based on arugments and filters or parameters,
 * a dynamic value like timestamp would always regenerate a cache key. Therefore
 * you can use `new DynamicValue(time())` for arguments or filters in order to fix
 * this problem.
 * 
 * @author Basil Suter <basil@nadar.io>
 * @since 2.1.0
 */
class DynamicValue
{
    private $_value;

    private $_key;

    /**
     * Generate dynamic cache value
     *
     * @param mixed $value
     * @param string|integer $key
     */
    public function __construct($value, $key = null)
    {
        $this->_value = $value;
        $this->_key = $key;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function getKey()
    {
        return $this->_key ?? strlen($this->_value);
    }

    public function __toString()
    {
        return $this->_value;
    }
}