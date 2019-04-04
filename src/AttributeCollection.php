<?php

/* 
 * Define a collection of attributes.
 */

namespace Programster\JsonValidator;

final class AttributeCollection extends \ArrayObject
{
    public function __construct(Attribute ...$attributes)
    {
        parent::__construct($attributes);
    }


    public function append($attribute) 
    {
        if ($attribute instanceof Attribute)
        {
            parent::append($attribute);
        }
        else
        {
            throw new \Exception("Cannot append non Attribute to a " . __CLASS__);
        }
    }

    
    public function offsetSet($index, $newval) 
    {
        if ($newval instanceof Attribute)
        {
            parent::offsetSet($index, $newval);
        }
        else
        {
            throw new \Exception("Cannot add a non Attribute value to a " . __CLASS__);
        }
    }
}