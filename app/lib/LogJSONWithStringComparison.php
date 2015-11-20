<?php
/**
 * VisualPHPUnit
 *
 * PHP Version 5.3<
 *
 * @author    Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2015 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace app\lib;

use \PHPUnit_Framework_ExpectationFailedException;
use \PHPUnit_Framework_Test;
use \PHPUnit_Framework_AssertionFailedError;
use \PHPUnit_Util_Log_JSON;

/**
 * LogJSONWithStringComparison
 *
 * Class for handeling string comparison failurs
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class LogJSONWithStringComparison extends PHPUnit_Util_Log_JSON
{

    /**
     * (non-PHPdoc)
     *
     * @see \PHPUnit_Util_Log_JSON::addFailure()
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        if ($e instanceof PHPUnit_Framework_ExpectationFailedException && $e->getComparisonFailure()) {
            $newMessage = $e->getComparisonFailure()->getMessage()
            . ' [Expected] \'' . $e->getComparisonFailure()->getExpected()
            . '\'' . ' [Actual] \'' . $e->getComparisonFailure()->getActual() . '\'';
            
            $newError = new PHPUnit_Framework_ExpectationFailedException($newMessage, $e->getComparisonFailure(), $e);
            parent::addFailure($test, $newError, $time);
        } else {
            parent::addFailure($test, $e, $time);
        }
    }
}
