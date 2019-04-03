<?php

/* 
 * 
 */

namespace Programster\JsonValidator\Testing\Tests;

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

        $helloAttribute = new \Programster\JsonValidator\Attribute(
            "hello", 
            new \Programster\JsonValidator\RegExpValidator("/world/")
        );
        
        $worldAttribute = new \Programster\JsonValidator\Attribute(
            "world", 
            new \Programster\JsonValidator\ObjectValidator(array($helloAttribute), array())
        );
        

        $objectValidator = new \Programster\JsonValidator\ObjectValidator(
            array($helloAttribute), 
            array($worldAttribute)
        );

        $jsonValidator = new \Programster\JsonValidator\JsonValidator($objectValidator);
        $result = $jsonValidator->validate($jsonString);

        print "result: " . print_r($result, true) . PHP_EOL;
        print "message: " . $jsonValidator->getErrorMessage() . PHP_EOL;
    }
}

