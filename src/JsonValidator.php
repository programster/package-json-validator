<?php

/* 
 * 
 */

namespace iRAP\JsonValidator;

class JsonValidator
{
    private $m_validator;
    
    
    public function __construct(ValidatorInterface $validator)
    {
        $this->m_validator = $validator;
    }
    
    
    public function getErrorMessage(): string 
    {
        return $this->m_validator->getErrorMessage();
    }
    
    
    public function validate(string $jsonString)
    {
        $input = json_decode($jsonString);
        return $this->m_validator->validate($input);
    }
}
