<?php
namespace visualphpunit;

use \PHPUnit_Framework_TestCase;

class PUTest3 extends PHPUnit_Framework_TestCase
{

    public function testThis()
    {
        $key = 'test';
        $value = 'test';
        print_r('some stuff');
        $this->assertEquals($key, $value, 'test_this() failed!');
    }

    public function testThisToo()
    {
        $key = 'test';
        $value = 'test';
        print_r('some stuff');
        $this->assertEquals($key, $value, 'test_this_too() failed!');
    }
}
