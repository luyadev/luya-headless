<?php

namespace luya\headless\cache;

/**
 * Generate Dynamic Value expression.
 *
 * When cache keys are generated based on arguments and filters or parameters,
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

    /**
     * Get the value of the dynamic argument.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Get the cache key for this dynamic value argument. If not defined the length of the value string is returned.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->_key ?? strlen($this->_value);
    }
}
