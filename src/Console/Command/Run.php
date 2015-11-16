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

/**
 * Visualphpunit console command
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class Run extends Command
{

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
            ->addOption('archive', 'a', InputOption::VALUE_NONE, 'Archive test suite result');
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
        if (! empty($input->getArgument('files'))) {
            $parser = new Parser();
            $result = $parser->run($input->getArgument('files'));
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

    /**
     * Get database connection
     *
     * Get connection to database to store result of suite
     *
     * @return \Doctrine\DBAL\Connection
     */
    private function getDbConnection()
    {
        $appRoot = realpath(__DIR__ . '/../../..');
        $config = json_decode(file_get_contents($appRoot . '/vpu.json'), true);
        $connectionParams = array(
            'path' => $appRoot . '/vpu.db',
            'driver' => $config['config']['database']['driver']
        );
        return DriverManager::getConnection($connectionParams, new Configuration());
    }
}
