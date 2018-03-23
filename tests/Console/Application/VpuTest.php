<?php
namespace Visualphpunit\Test\Application\Command;

use Visualphpunit\Console\Application\Vpu;
use PHPUnit\Framework\TestCase;

class VpuTest extends TestCase
{

    /**
     * This is purposely an extremely simple test
     *
     * http://api.symfony.com/2.7/Symfony/Component/Console/Tester/CommandTester.html
     * dos not work with phpunit versions > 4.1.* due to the way output buffering is managed.
     *
     * @test
     */
    public function correctInstantiation()
    {
        $application = new Vpu();
        $this->assertInstanceOf('Visualphpunit\Console\Application\Vpu', $application);
    }
}
