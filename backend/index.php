<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.6<
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2016 VisualPHPUnit
 * @license http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
require __DIR__ . '/../vendor/autoload.php';
use Visualphpunit\Api\Application\Vpu;

/**
 * Prevent app to be added to the $GLOBALS
 *
 * @return void
 */
function run()
{
    $app = new Vpu();
    $app->run();
}
run();
