<?php

/* 
 * Validate an input using a regexp
 */

namespace Programster\JsonValidator;

class RegExpValidator implements ValidatorInterface
{
    private $m_errorMessage = "";
    private $m_pattern;
    
    
    public function __construct($regexp)
    {
        $this->m_pattern = $regexp;
    }
    
    
    public function validate($input): bool 
    {
        $isValid = false;
        
        if (is_object($input))
        {
            $stringForm = json_encode($input);
        }
        else
        {
            $stringForm = $input;
            
            // To test against json true/false/null, convert it to a string.
            if ($input === false)
            {
                $stringForm = "false";
            }
            elseif ($input === true)
            {
                $stringForm = "true";
            }
            
            if ($input === null)
            {
                $stringForm = "null";
            }
        }
        
        $pregResult = preg_match($this->m_pattern, $stringForm);

        if ($pregResult === FALSE)
        {
            $this->m_errorMessage = "There is a problem with your regexp: " . $this->m_pattern;
        }
        elseif ($pregResult === 0)
        {
            $this->m_errorMessage = "Pattern " . $this->m_pattern . " did not match: '{$stringForm}'.";
        }
        else
        {
            $isValid = true;
        }
        
        return $isValid;
    }
    
    
    # Accessors
    public function getErrorMessage(): string { return $this->m_errorMessage; }
}

