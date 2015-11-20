<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a gui for phpunit
 *
 * PHP Version 5.3<
 *
 * @author    Nick Sinopoli <NSinopoli@gmail.com>
 * @copyright 2011-2015 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
require dirname(__DIR__) . '/config/bootstrap.php';

$request = new \nx\core\Request();
$dispatcher = new \nx\core\Dispatcher();
$dispatcher->handle($request, \app\config\Routes::getRoutes());
