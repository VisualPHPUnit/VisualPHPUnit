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
 * Data test
 *
 * Testing class
 *
 * @author Nick Sinopoli <NSinopoli@gmail.com>
 */
class DateTest extends PHPUnit_Framework_TestCase
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
        $key = 'test';
        $value = 'test';
        $this->assertEquals($key, $value, 'test_this() failed!');
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
        print_r('foo { breaks: this } bar');
        print_r('foo breaks: this { bar');
        $this->assertEquals($key, $value, 'test_this_too() failed!');
    }
}
