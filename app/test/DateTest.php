<?php
namespace visualphpunit;

use \PHPUnit_Framework_TestCase;

class DateTest extends PHPUnit_Framework_TestCase
{

    public function testThis()
    {
        $key = 'test';
        $value = 'test';
        $this->assertEquals($key, $value, 'test_this() failed!');
    }

    public function testThisToo()
    {
        $key = 'test';
        $value = 'test';
        print_r('foo { breaks: this } bar');
        print_r('foo breaks: this { bar');
        $this->assertEquals($key, $value, 'test_this_too() failed!');
    }
}
