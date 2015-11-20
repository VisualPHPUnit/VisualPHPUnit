<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.6<
 *
 * @author    Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2015 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace Visualphpunit\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Visualphpunit\Core\Parser;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use Visualphpunit\Core\Suite;
use Visualphpunit\Core\Test;

/**
 * Visualphpunit console command
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class Run extends Command
{

    /**
     * Configuration options for starting vpu as a service
     *
     * @var string[]
     */
    private $serverConfig;

    /**
     * Application root
     *
     * @var string
     */
    private $appRoot;

    /**
     *
     * {@inheritDoc}
     *
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this->setName('vpu')
            ->addArgument('files', InputArgument::IS_ARRAY, 'List of test files')
            ->addOption('archive', 'a', InputOption::VALUE_NONE, 'Archive test suite result')
            ->addOption('start', 's', InputOption::VALUE_NONE, 'Start VPU')
            ->addOption('stop', 't', InputOption::VALUE_NONE, 'Stop VPU');
        $this->serverConfig = [
            'frontend' => [
                'host' => 'localhost',
                'port' => 8000,
                'docroot' => '../dist'
            ],
            'backend' => [
                'host' => 'localhost',
                'port' => 8001,
                'docroot' => '../backend'
            ]
        ];
        $this->appRoot = realpath(__DIR__ . '/../../..');
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->setFormatter(new OutputFormatter(true));
        if ($input->getOption('start')) {
            $this->start();
            $output->writeln('<comment>VPU started</comment>');
        } elseif ($input->getOption('stop')) {
            $this->stop($output);
            $output->writeln('<comment>VPU stopped</comment>');
        } else {
            if (! empty($input->getArgument('files'))) {
                $parser = new Parser();
                $result = $parser->run($input->getArgument('files'));
                Test::createTable($this->getDbConnection());
                Test::store($this->getDbConnection(), $result);
                if ($input->getOption('archive')) {
                    Suite::createTable($this->getDbConnection());
                    Suite::store($this->getDbConnection(), $result);
                    if ($output->isVerbose()) {
                        $output->writeln('<comment>Test suite archived</comment>');
                    }
                }
            } else {
                $output->writeln('<error>No files where supplied. Use -h for help.</error>');
            }
        }
    }

    /**
     * Start vpu service
     *
     * @return void
     */
    private function start()
    {
        foreach ($this->serverConfig as $server => $config) {
            $cmd = sprintf('php -S %s:%d -t %s >/dev/null 2>&1 & echo $!', $config['host'], $config['port'], $config['docroot']);
            $output = array();
            exec($cmd, $output);
            $pid = (int) $output[0];
            file_put_contents($this->appRoot . '/' . $server . '.pid', $pid);
        }
    }

    /**
     * Stop vpu service
     *
     * @return void
     */
    private function stop()
    {
        foreach ($this->serverConfig as $server => $config) {
            $path = $this->appRoot . '/' . $server . '.pid';
            if (file_exists($path)) {
                exec('kill ' . file_get_contents($path));
                unlink($path);
            }
        }
    }

    /**
     * Get database connection
     *
     * Get connection to database to store result of suite
     *
     * @return \Doctrine\DBAL\Connection
     */
    private function getDbConnection()
    {
        $config = json_decode(file_get_contents($this->appRoot . '/vpu.json'), true);
        $connectionParams = array(
            'path' => $this->appRoot . '/vpu.db',
            'driver' => $config['config']['database']['driver']
        );
        return DriverManager::getConnection($connectionParams, new Configuration());
    }
}
