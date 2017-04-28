<?php

/*
 * Validate an object
 * Unlike the ObjectValidator, this object validator only takes one RegExpAttribute instead of 
 * multiple, and it is up to you to define the minimum/maximum number of attributes the object
 * can have, or if there is no limit at all.
 */


namespace iRAP\JsonValidator;


class RegExpObjectValidator implements ValidatorInterface
{
    private $m_errorMessage = "";
    private $m_regExpAttribute;
    
    
    public function __construct(RegExpAttribute $regexpAttribute) 
    {
        $this->m_regExpAttribute = $regexpAttribute;
        
        $attributeName = $regexpAttribute->getNameRegExp();
        $this->m_requiredAttributeNames[] = $attributeName;
        
        if (isset($this->m_indexedAttributes[$attributeName]))
        {
            throw new \Exception("Duplicate attribute definition on: " . $attributeName);
        }
        
        $this->m_indexedAttributes[$attributeName] = $regexpAttribute;
    }
    
    
    public function validate($input): bool 
    {        
        try
        {
            if (!is_object($input))
            {
                throw new \Exception("Input was not an object: " . print_r($input, true));
            }
            
            $arrayForm = (array) $input;
            
            foreach ($arrayForm as $attributeName => $attributeValue)
            {
                $pattern = $this->m_regExpAttribute->getNameRegExp();
                $matchResult = preg_match($pattern, $attributeName);
                
                if ($matchResult === FALSE)
                {
                    throw new \Exception("There is something wrong with your RegExp: " . $pattern);
                }
                
                if ($matchResult === 0)
                {
                    throw new \Exception("Attribute " . $attributeName . " did not match regexp: " . $pattern);
                }
                
                $valueValidator = $this->m_regExpAttribute->getValueValidator();
                
                if (!$valueValidator->validate($attributeValue))
                {
                    throw new \Exception($valueValidator->getErrorMessage());
                }
            }
            
            $passed = true;
        } 
        catch (\Exception $ex) 
        {
            $passed = false;
            $this->m_errorMessage = $ex->getMessage();
        }
        
        return $passed;
    }
    
    
    public function getErrorMessage(): string { return $this->m_errorMessage; }
}
