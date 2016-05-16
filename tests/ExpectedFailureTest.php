<?php
namespace Visualphpunit\Test;

class ExpectedFailureTest extends \PHPUnit_Framework_TestCase
{

    /**
     * This is purposely not implemented to demonstrated the output in VPU
     *
     * @test
     */
    public function incompleteTest()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
    
    /**
     * This is purposely failing to demonstrated the output in VPU
     *
     * @test
     */
    public function throwAnException()
    {
        throw new \Exception('This test results in an error on purpose.');
    }
    
    /**
     * This is purposely skipped to demonstrated the output in VPU
     *
     * @test
     */
    public function skipThisTest()
    {
        if (! extension_loaded('something_bogus')) {
            $this->markTestSkipped('The something_bogus extension is not available.');
        }
    }
    
    /**
     * This is purposely failing to demonstrated the output in VPU
     *
     * @test
     */
    public function thisTestShouldFail()
    {
        $expected = 'expected';
        $actual   = 'actual';
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * This is purposely failing to demonstrated the output in VPU
     *
     * @test
     */
    public function thisTestShouldFailToo()
    {
        $expected = 1;
        $actual   = 2;
        $this->assertEquals($expected, $actual);
    }
}