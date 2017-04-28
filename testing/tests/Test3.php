<?php

/* 
 * 
 */

namespace iRAP\JsonValidator\Testing\Tests;

class Test3
{
    public function run()
    {
        $myObj = array(
            "hello" => "world",
            "world" => array(
                "hello" => "world"
            )
        );

        $jsonString = json_encode($myObj);

        $helloAttribute = new \iRAP\JsonValidator\Attribute(
            "hello", 
            new \iRAP\JsonValidator\RegExpValidator("/world/")
        );
        
        $worldAttribute = new \iRAP\JsonValidator\Attribute(
            "world", 
            new \iRAP\JsonValidator\ObjectValidator(array($helloAttribute), array())
        );
        

        $objectValidator = new \iRAP\JsonValidator\ObjectValidator(
            array($helloAttribute), 
            array($worldAttribute)
        );

        $jsonValidator = new \iRAP\JsonValidator\JsonValidator($objectValidator);
        $result = $jsonValidator->validate($jsonString);

        print "result: " . print_r($result, true) . PHP_EOL;
        print "message: " . $jsonValidator->getErrorMessage() . PHP_EOL;
    }
}

