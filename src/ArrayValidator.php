<?php

/* 
 * A rule for validating the JSON string is an array.
 */

namespace iRAP\JsonValidator;

class ArrayValidator implements ValidatorInterface
{
    private $m_valueValidator;
    private $m_min;
    private $m_max;
    private $m_expectedNum;
    
    
    /**
     * Create an object to validate a JSON array of items.
     * @param \iRAP\JsonValidator\ValidatorInterface $valueValidator - an object to validate the items within the array.
     * @param int $min - the minimum number of items the array needs to have. Default 0 for any number of items.
     * @param int $max - the maximum number of items the array can have. Default -1 for not set.
     * @param int $expectedCount - the exact number of items the array can have. Default -1 for not set.
     */
    public function __construct(ValidatorInterface $valueValidator, int $min=0, int $max=-1, int $expectedCount=-1)
    {
        $this->m_valueValidator = $valueValidator;
        $this->m_min = $min;
        $this->m_max = $max;
        $this->m_expectedNum = $expectedCount;
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
            
            foreach ($input as $value)
            {
                if (!$this->m_valueValidator->validate($value))
                {
                    $failed = true;
                    $this->m_errorMessage = $this->m_valueValidator->getErrorMessage();
                    break;
                }
            }
            
            if (count($input) < $this->m_min)
            {
                $this->m_errorMessage = "Number of items didn't meet minimum of: " . $this->m_min;
                $failed = true;
            }
            
            if ($this->m_max > -1 && count($input) > $this->m_max) // 0 is a legit max to have set.
            {
                $this->m_errorMessage = "Number of items exceeded max of: " . $this->m_max;
                $failed = true;
            }
            
            if ($this->m_expectedNum > -1 && count($input) !== $this->m_expectedNum)
            {
                $this->m_errorMessage = "Number of items did not match expected number: " . $this->m_expectedNum;
                $failed = true;
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