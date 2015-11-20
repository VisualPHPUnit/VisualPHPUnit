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
 * Skipped test
 *
 * Testing class
 *
 * @author Nick Sinopoli <NSinopoli@gmail.com>
 */
class SkippedTest extends PHPUnit_Framework_TestCase
{

    /**
     * Setup the tests
     *
     * @return void
     */
    protected function setUp()
    {
        if (! extension_loaded('something_bogus')) {
            $this->markTestSkipped('The something_bogus extension is not available.');
        }
    }

    /**
     * Test one thing
     *
     * This test is just a dummy
     *
     * @test
     */
    public function oneThing()
    {
        $key = 'test';
        $value = 'value';
        print_r('some stuff');
        $this->assertEquals($key, $value, 'test_something_else() failed!');
    }
}
