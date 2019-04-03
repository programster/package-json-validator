<?php

/* 
 * 
 */

namespace Programster\JsonValidator\Testing\Tests;

class Test1
{
    public function run()
    {
        $myObj = array(
            "hello" => "world"
        );

        $jsonString = json_encode($myObj);

        $requiredAttributes = array(
            new \Programster\JsonValidator\Attribute("hello", new \Programster\JsonValidator\RegExpValidator("/world/"))
        );

        $objectValidator = new \Programster\JsonValidator\ObjectValidator(
            $requiredAttributes, 
            $optionalAttributes = array()
        );

        $jsonValidator = new \Programster\JsonValidator\JsonValidator($objectValidator);
        $result = $jsonValidator->validate($jsonString);

        print "result: " . print_r($result, true) . PHP_EOL;
        print "message: " . $jsonValidator->getErrorMessage() . PHP_EOL;
    }
}

