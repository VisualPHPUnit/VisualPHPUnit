<?php
namespace visualphpunit;

use \PHPUnit_Framework_TestCase;

class IncompleteTest extends PHPUnit_Framework_TestCase
{

    public function testSomething()
    {
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');
        
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testSomethingElse()
    {
        $key = 'test';
        $value = 'test';
        print_r('some stuff');
        $this->assertEquals($key, $value, 'test_something_else() failed!');
    }
}
