<?php

/* 
 * A rule for validating the JSON string is an array.
 */

namespace iRAP\JsonValidator;

class ArrayValidator implements ValidatorInterface
{
    private $m_valueValidator;
    
    
    public function __construct(ValidatorInterface $valueValidator)
    {
        $this->m_valueValidator = $valueValidator;
    }
    
    
    public function validate($input)
    {
        $isValid = false;
        
        if (is_array($input))
        {
            $failed = false;
            
            foreach ($input as $value)
            {
                if (!$this->m_valueValidator->validate($value))
                {
                    $failed = true;
                    $this->m_errorMessage = $this->m_valueValidator->getErrorMessage();
                    break;
                }
            }
            
            if (!$failed)
            {
                $isValid = true;
            }
        }
        else
        {
            $stringForm = json_encode($input, JSON_PRETTY_PRINT);
            $this->m_errorMessage = $stringForm . " is not an array.";
        }
        
        return $isValid;
    }
    
    
    public function getErrorMessage(): string { return $this->m_errorMessage; }
}