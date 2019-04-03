<?php

/*
 * Validate an object
 */


namespace Programster\JsonValidator;


class ObjectValidator implements ValidatorInterface
{
    private $m_errorMessage = "";
    private $m_requiredAttributeNames = array(); // just the names of the requires attributes.
    private $m_optionalAttributeNames = array(); // just the names of the optional attributes
    private $m_requiredAttributes; // collection of attribute that must be in the object
    private $m_optionalAttributes; // collection attributes that may or may be in the object
    private $m_indexedAttributes = array(); // req and opt attribute objs indexed by name
    
    
    public function __construct(AttributeCollection $requiredAttributes, AttributeCollection $optionalAttributes=null) 
    {
        if ($optionalAttributes === null)
        {
            $optionalAttributes = new AttributeCollection();
        }
        
        $this->m_requiredAttributes = $requiredAttributes;
        $this->m_optionalAttributes = $optionalAttributes;
        
        foreach ($this->m_requiredAttributes as $attribute)
        {
            /* @var $attribute \Programster\JsonValidator\Attribute */
            $attributeName = $attribute->getName();
            $this->m_requiredAttributeNames[] = $attributeName;
            
            if (isset($this->m_indexedAttributes[$attributeName]))
            {
                throw new \Exception("Duplicate attribute definition on: " . $attributeName);
            }
            
            $this->m_indexedAttributes[$attributeName] = $attribute;
        }
        
        foreach ($this->m_optionalAttributes as $optionalAttribute)
        {
            /* @var $attribute \Programster\JsonValidator\Attribute */
            $attributeName = $optionalAttribute->getName();
            $this->m_optionalAttributeNames[] = $attributeName;
            
            if (isset($this->m_indexedAttributes[$attributeName]))
            {
                throw new \Exception("Duplicate attribute definition on: " . $attributeName);
            }
            
            $this->m_indexedAttributes[$attributeName] = $optionalAttribute;
        }
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
            $attributeNames = array();
            
            foreach ($arrayForm as $attributeName => $attributeValue)
            {
                $attributeNames[] = $attributeName;
            }
            
            $missingAttributes = array_diff($this->m_requiredAttributeNames, $attributeNames);
            
            if (count($missingAttributes) > 0)
            {
                $msg = "Missing required attributes: " . print_r($missingAttributes, true);
                throw new \Exception($msg);
            }
            
            $allAllowedAttributes = array_merge(
                $this->m_requiredAttributeNames, 
                $this->m_optionalAttributeNames
            );
            
            $excessAttributes = array_diff($attributeNames, $allAllowedAttributes);
            
            if (count($excessAttributes) > 0)
            {
                $msg = "Attributes found that weren't required or optional: " . 
                       print_r($excessAttributes, true);
                
                throw new \Exception($msg);
            }
            
            foreach ($arrayForm as $attributeName => $attributeValue)
            {
                $attributeObj = $this->m_indexedAttributes[$attributeName];
                $valueValidator = $attributeObj->getValueValidator();
                
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
    
    
    public function getErrorMessage(): string 
    {
        return $this->m_errorMessage;
    }
}
