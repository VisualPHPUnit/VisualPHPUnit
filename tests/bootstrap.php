<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.6<
 *
 * @author    Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2016 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */

//You need to update the function name if you want to run vpu's own tests via vpu.phar
if (! function_exists('composerRequire6f35294d06ff43eeb876443f450df6c4')) {
    require_once realpath(__DIR__ . '/../vendor/autoload.php');
}
