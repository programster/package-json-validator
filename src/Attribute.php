<?php

/* 
 * Define an attribute that should appear in a JSON object.
 * Each attribute has a name, and a value to validate.
 */

namespace Programster\JsonValidator;

class Attribute
{
    private $m_name;
    private $m_valueValidator;
    
    
    public function __construct($name, ValidatorInterface $valueValidator)
    {
        $this->m_name = $name;
        $this->m_valueValidator = $valueValidator;
    }
    
    
    # Accessors
    public function getName()  { return $this->m_name; }
    public function getValueValidator() : ValidatorInterface { return $this->m_valueValidator; }
}