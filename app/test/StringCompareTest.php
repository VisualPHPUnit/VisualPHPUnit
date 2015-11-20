<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.3<
 *
 * @author    Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2015 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace visualphpunit;

use \PHPUnit_Framework_TestCase;

/**
 * String compare test
 *
 * Testing class
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class StringCompareTest extends PHPUnit_Framework_TestCase
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
        $key = 'string';
        $value = 'different string';
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
