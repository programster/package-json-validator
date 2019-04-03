<?php

/*
 * Test this package fully works. This is just a script for now because testing is so small, but
 * if necessary, put all the tests in the "tests" folder and kick them off from here.
 */


require_once(__DIR__ . '/../vendor/autoload.php');

$testFiles = scandir(__DIR__ . '/tests');
$tests = array();

$fileNameConverter = function($testFilename) use (&$tests) {
    if ($testFilename !== '.' && $testFilename !== '..')
    {
        $tests[] = str_replace(".php", "", $testFilename);
    }
};

array_walk($testFiles, $fileNameConverter);
print_r($tests);

foreach ($tests as $test)
{
    print "Runing test: " . $test . PHP_EOL;
    $testFullName = '\\Programster\\JsonValidator\\Testing\\Tests\\' . $test;    
    $test = new $testFullName();
    $test->run();
}