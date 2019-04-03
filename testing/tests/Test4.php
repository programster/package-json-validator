<?php

/* 
 * A test of handling an object with an unknown number of attributes which are the id of the 
 * objects they hold as values.
 */

namespace Programster\JsonValidator\Testing\Tests;

class Test4
{
    public function run()
    {
        $myObj = array(
            "datasets" => array(
                "1" => array(
                    "id" => 1,
                    "name" => "dataset 1"
                ),
                "2" => array(
                    "id" => 2,
                    "name" => "dataset 2"
                ),
                "3" => array(
                    "id" => 3,
                    "name" => "dataset 3"
                )
            )
        );
        
        
        $jsonString = json_encode($myObj);
        
        $reqDatasetAttributes = array(
            new \Programster\JsonValidator\Attribute("id", new \Programster\JsonValidator\RegexpValidator("/^[0-9]+$/")),
            new \Programster\JsonValidator\Attribute("name", new \Programster\JsonValidator\RegexpValidator("/^[a-zA-Z0-9_ -]+$/")),
        );
        
        $datasetObjValidator = new \Programster\JsonValidator\ObjectValidator($reqDatasetAttributes);
        
        $datasetCollectionValidator = new \Programster\JsonValidator\RegExpAttribute(
            "/[0-9]+/", 
            $datasetObjValidator
        );
        
        $datasetCollectionValidator->setMaximumNumberOfMatches(2);
        
        $datasetCollectionObject = new \Programster\JsonValidator\RegExpObjectValidator($datasetCollectionValidator);
        
        $datasetsAttribute = new \Programster\JsonValidator\Attribute(
            "datasets", 
            $datasetCollectionObject
        );
        
        $responseObjectValidator = new \Programster\JsonValidator\ObjectValidator(
            array($datasetsAttribute) 
        );

        $jsonValidator = new \Programster\JsonValidator\JsonValidator($responseObjectValidator);
        $result = $jsonValidator->validate($jsonString);

        print "result: " . print_r($result, true) . PHP_EOL;
        print "message: " . $jsonValidator->getErrorMessage() . PHP_EOL;
    }
}

