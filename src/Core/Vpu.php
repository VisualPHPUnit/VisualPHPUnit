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
namespace Visualphpunit\Core;

use \PHPUnit_Framework_TestSuite;
use \PHPUnit_Framework_TestResult;
use \PHPUnit_Framework_ExpectationFailedException;
use \PHPUnit_Framework_SelfDescribing;
use \Exception;

/**
 * Visualphpunit core
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class Vpu
{
    public function run($tests)
    {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestFiles([
            '/Users/jsf/Web/VisualPHPUnit/app/test/DateTest.php',
            '/Users/jsf/Web/VisualPHPUnit/app/test/PUTest.php',
            '/Users/jsf/Web/VisualPHPUnit/app/test/PUTest2.php',
            '/Users/jsf/Web/VisualPHPUnit/app/test/SkippedTest.php',
            '/Users/jsf/Web/VisualPHPUnit/app/test/IncompleteTest.php',
            '/Users/jsf/Web/VisualPHPUnit/app/test/StringCompareTest.php',
            '/Users/jsf/Web/VisualPHPUnit/app/test/sample_dir/IncompleteTest2.php',
            '/Users/jsf/Web/VisualPHPUnit/app/test/sample_dir/PUTest3.php',
            '/Users/jsf/Web/VisualPHPUnit/app/test/sample_dir/PUTest4.php'
        ]);
        return $this->parseTestSuite($suite->run(new PHPUnit_Framework_TestResult()));
    }

    /**
     * Parse the test result
     *
     * @param \PHPUnit_Framework_TestResult $result            
     * @return array
     */
    private function parseTestSuite($result)
    {
        $passed = 0;
        $error = 0;
        $failed = 0;
        $notImplemented = 0;
        $skipped = 0;
        
        $tests = [];
        foreach ($result->passed() as $key => $value) {
            $tests[] = $this->parseTest('passed', $key);
            $passed ++;
        }
        foreach ($result->failures() as $obj) {
            $tests[] = $this->parseTest('failed', $obj);
            $failed ++;
        }
        foreach ($result->skipped() as $obj) {
            $tests[] = $this->parseTest('skipped', $obj);
            $skipped ++;
        }
        foreach ($result->notImplemented() as $obj) {
            $tests[] = $this->parseTest('notImplemented', $obj);
            $notImplemented ++;
        }
        foreach ($result->errors() as $obj) {
            $tests[] = $this->parseTest('error', $obj);
            $error ++;
        }
        
        usort($tests, function ($a, $b) {
            return strnatcmp($a['class'], $b['class']);
        });
        
        $data = [
            'time' => $result->time(),
            'total' => count($tests),
            'passed' => $passed,
            'error' => $error,
            'failed' => $failed,
            'notImplemented' => $notImplemented,
            'skipped' => $skipped,
            'tests' => $tests
        ];
        return $data;
    }

    private function parseTest($status, $test)
    {
        if (is_object($test)) {
            return [
                'class' => $this->explodeTestName($test->getTestName())['class'],
                'name' => $this->explodeTestName($test->getTestName())['method'],
                'friendly-name' => $this->friendlyName($this->explodeTestName($test->getTestName())['method']),
                'status' => $status,
                'message' => $test->thrownException()->getMessage(),
                'expected' => $this->getComparison($test->thrownException())['expected'],
                'actual' => $this->getComparison($test->thrownException())['actual']
            ];
        } else {
            return [
                'class' => $this->explodeTestName($test)['class'],
                'name' => $this->explodeTestName($test)['method'],
                'friendly-name' => $this->friendlyName($this->explodeTestName($test)['method']),
                'status' => $status,
                'message' => '',
                'expected' => '',
                'actual' => ''
            ];
        }
    }


    private function friendlyName($camelCaseString)
    {
        $re = '/(?<=[a-z])(?=[A-Z])/x';
        $a = preg_split($re, $camelCaseString);
        $a[0] = ucfirst($a[0]);
        return join($a, " ");
    }

    private function explodeTestName($testName)
    {
        preg_match('/([a-zA-Z0-9]+)::([a-zA-Z0-9]+)$/', $testName, $matches);
        return [
            'class' => $matches[1],
            'method' => $matches[2]
        ];
    }

    private function getComparison(Exception $e)
    {
        if ($e instanceof PHPUnit_Framework_SelfDescribing) {
            if ($e instanceof PHPUnit_Framework_ExpectationFailedException && $e->getComparisonFailure()) {
                return [
                    'expected' => $e->getComparisonFailure()->getExpected(),
                    'actual' => $e->getComparisonFailure()->getActual()
                ];
            }
        }
        return [
            'expected' => '',
            'actual' => ''
        ];
    }
}