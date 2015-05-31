<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.3<
 *
 * @author    Nick Sinopoli <NSinopoli@gmail.com>
 * @copyright 2011-2015 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace visualphpunit;

use \PHPUnit_Framework_TestCase;

/**
 * Incomplete test 2
 *
 * Testing class
 *
 * @author Nick Sinopoli <NSinopoli@gmail.com>
 */
class IncompleteTest2 extends PHPUnit_Framework_TestCase
{

    /**
     * Test one thing
     *
     * This test is just a dummy
     *
     * @test
     */
    public function oneThing()
    {
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');
        
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * Test another thing
     *
     * This test is just a dummy
     *
     * @test
     */
    public function anotherThing()
    {
        $key = 'test';
        $value = 'test';
        print_r('some stuff');
        $this->assertEquals($key, $value, 'test_something_else() failed!');
    }
}
