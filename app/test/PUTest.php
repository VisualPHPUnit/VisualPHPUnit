<?php
namespace visualphpunit;

use \PHPUnit_Framework_TestCase;

class PUTest extends PHPUnit_Framework_TestCase
{

    public function testThis()
    {
        $key = 'test';
        $value = 'value';
        print_r('some stuff');
        $this->assertEquals($key, $value, 'test_this() failed!');
    }

    public function testThisToo()
    {
        somestr;
        $key = 'test';
        $value = 'value';
        $this->assertEquals($key, $value, 'test_this_too() failed!');
    }
}
