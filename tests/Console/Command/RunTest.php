<?php
namespace Visualphpunit\Test\Console\Command;

use Visualphpunit\Console\Command\Run;
use PHPUnit\Framework\TestCase;

class RunTest extends TestCase
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
        $command = new Run();

        $this->assertInstanceOf('Visualphpunit\Console\Command\Run', $command);
    }
}
