<?php

/* 
 * A rule for validating the JSON string is an array with the correct values.
 * Unlike ArrayValidator which runs the same value validator against all the values, this expects a value validator
 * for each value (in the expected order)
 */

namespace Programster\JsonValidator;

class ArrayValidatorSpecific implements ValidatorInterface
{
    private $m_valueValidators;
    
    
    /**
     * Create an object to validate a JSON array of items.
     * @param \Programster\JsonValidator\ValidatorInterface $valueValidator - an object to validate the items within the array.
     * @param int $min - the minimum number of items the array needs to have. Default 0 for any number of items.
     * @param int $max - the maximum number of items the array can have. Default -1 for not set.
     * @param int $expectedCount - the exact number of items the array can have. Default -1 for not set.
     */
    public function __construct(ValidatorInterface... $valueValidators)
    {
        $this->m_valueValidators = $valueValidators;
    }
    
    
    /**
     * Validate the input which should be an array from an earlier json_decode()
     * @param type $input
     * @return bool
     */
    public function validate($input) : bool
    {
        $isValid = false;
        
        if (is_array($input))
        {
            $failed = false;
            
            if (count($input) !== count($this->m_valueValidators))
            {
                $failed = true;
                $this->m_errorMessage = "Number of items didn't meet required number: " . count($this->m_valueValidators);
            }
            
            if (!$failed)
            {
                foreach ($input as $index => $value)
                {
                    $valueValidator = $this->m_valueValidators[$index];

                    if ($valueValidator->validate($value) !== true)
                    {
                        $failed = true;
                        $this->m_errorMessage = $valueValidator->getErrorMessage();
                        break;
                    }
                }
            }
            
            if ($failed === false)
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