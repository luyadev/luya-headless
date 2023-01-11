<?php

namespace luya\headless\base;

use ReflectionClass;
use ReflectionProperty;
use luya\headless\Exception;

/**
 * Base Model.
 *
 * Getter/Setter methods logic from Yii Framework BaseObject. In addition you can load data into
 * the class property in order to have a model similar behavior.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class BaseModel
{
    /**
     * Initial the
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->load($data);
    }
    
    /**
     * Load the attribute data into the model, where array key is the property of the class object.
     *
     * @param array $data The data where key is the object property and the value, the value to assign.
     */
    public function load(array $data)
    {
        foreach ($data as $attribute => $value) {
            if ($this->canSetProperty($attribute)) {
                $this->{$attribute} = $value;
            }
        }
    }

    private $_oldValues = [];

    /**
     * Refresh attribute values and store old values.
     *
     * @param array $data An array with key value parining which should override the existing value.
     */
    public function refresh(array $data)
    {
        foreach ($data as $attribute => $value) {
            if ($this->canSetProperty($attribute)) {
                $this->_oldValues[$attribute] = $this->{$attribute};
                $this->{$attribute} = $value;
            }
        }
    }

    /**
     * If refresh() is done, the old values can be accessed with oldValue($attribute).
     *
     * @param string $attribute
     * @return mixed
     */
    public function oldValue($attribute)
    {
        return isset($this->_oldValues[$attribute]) ? $this->_oldValues[$attribute] : false;
    }

    /**
     * Create an iterator of models for the current endpoint.
     *
     * @param array $data
     * @param string $keyColumn
     * @return static
     */
    public static function iterator(array $data, $keyColumn = null)
    {
        return BaseIterator::create(get_called_class(), $data, $keyColumn);
    }
    
    /**
     * Returns the list of attribute names.
     * By default, this method returns all public non-static properties of the class.
     * You may override this method to change the default behavior.
     *
     * @return array list of attribute names.
     */
    public function attributes()
    {
        $class = new ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }
        
        return $names;
    }

    /**
     * Convert to current object into an array with attributes and getters.
     *
     * @param array $attributes A list of arrays which should be covered, if empty array all attributes() are taken.
     * @param array $getters An optional list of getter names which should be used to generate an array. Assuming `getUser` the
     * name `user` needs to be taken.
     * @return array
     * @since 1.0.0
     */
    public function toArray(array $attributes = [], $getters = [])
    {
        $attributes = empty($attributes) ? $this->attributes() : $attributes;
        $attributes = array_merge($attributes, $getters);

        $array = [];
        foreach ($attributes as $name) {
            $array[$name] = $this->{$name};
        }

        return $array;
    }
    
    /**
     * Returns the value of an object property.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$value = $object->property;`.
     * @param string $name the property name
     * @return mixed the property value
     * @throws Exception if the property is not defined
     * @throws Exception if the property is write-only
     * @see __set()
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new Exception('Getting write-only property: ' . get_class($this) . '::' . $name);
        }
        throw new Exception('Getting unknown property: ' . get_class($this) . '::' . $name);
    }
    /**
     * Sets value of an object property.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$object->property = $value;`.
     * @param string $name the property name or the event name
     * @param mixed $value the property value
     * @throws Exception if the property is not defined
     * @throws Exception if the property is read-only
     * @see __get()
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new Exception('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new Exception('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Checks if a property is set, i.e. defined and not null.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `isset($object->property)`.
     *
     * Note that if the property is not defined, false will be returned.
     * @param string $name the property name or the event name
     * @return bool whether the named property is set (not null).
     * @see https://php.net/manual/en/function.isset.php
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }
        return false;
    }
    
    /**
     * Returns a value indicating whether a property is defined.
     *
     * A property is defined if:
     *
     * - the class has a getter or setter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * @param string $name the property name
     * @param bool $checkVars whether to treat member variables as properties
     * @return bool whether the property is defined
     * @see canGetProperty()
     * @see canSetProperty()
     */
    public function hasProperty($name, $checkVars = true)
    {
        return $this->canGetProperty($name, $checkVars) || $this->canSetProperty($name, false);
    }
    /**
     * Returns a value indicating whether a property can be read.
     *
     * A property is readable if:
     *
     * - the class has a getter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * @param string $name the property name
     * @param bool $checkVars whether to treat member variables as properties
     * @return bool whether the property can be read
     * @see canSetProperty()
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name);
    }
    /**
     * Returns a value indicating whether a property can be set.
     *
     * A property is writable if:
     *
     * - the class has a setter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * @param string $name the property name
     * @param bool $checkVars whether to treat member variables as properties
     * @return bool whether the property can be written
     * @see canGetProperty()
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'set' . $name) || $checkVars && property_exists($this, $name);
    }
    /**
     * Returns a value indicating whether a method is defined.
     *
     * The default implementation is a call to php function `method_exists()`.
     * You may override this method when you implemented the php magic method `__call()`.
     * @param string $name the method name
     * @return bool whether the method is defined
     */
    public function hasMethod($name)
    {
        return method_exists($this, $name);
    }
}
