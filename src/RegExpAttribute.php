<?php

/* 
 * An attribute that uses a regular expression to match against any number of names. 
 * This is used for the RegexpObjectValidator only.
 */

namespace Programster\JsonValidator;

class RegExpAttribute
{
    private $m_nameRegexp;
    private $m_valueValidator;
    private $m_minimum = 0; // no minimum number of attributes to match
    private $m_maximum = -1; // no maximum number of attributes to match.
    
    
    /**
     * Create an attribute that uses a regexp to match any number of elements in the object
     * that uses this to match its attributes. 
     * @param string $namePattern - the regexp to match the attributes of the json object.
     * @param \Programster\JsonValidator\ValidatorInterface $valueValidator - a validator to validate
     *         the values that are matched to attributes that match the name pattern.
     * @param int $minMatches - the minimum number of attributes we are expecting to match to
     *                          pass validation.
     */
    public function __construct($namePattern, ValidatorInterface $valueValidator, $minMatches=0)
    {
        $this->m_minimum = $minMatches;
        $this->m_nameRegexp = $namePattern;
        $this->m_valueValidator = $valueValidator;
    }
    
    
    /**
     * Set the maximum number of attributes that should be in the object that this should match
     * in the object that has this attribute. The default is -1 for no limit.
     * @param int $max
     */
    public function setMaximumNumberOfMatches(int $max)
    {
        $this->m_maximum = $max;
    }
    
    
    # Accessors
    public function getMinumumNumberOfAttributes() { return $this->m_minimum; }
    public function getMaximumNumberOfAttributes() { return $this->m_maximum; }
    public function getNameRegExp() : String { return $this->m_nameRegexp; }
    public function getValueValidator() : ValidatorInterface { return $this->m_valueValidator; }
}