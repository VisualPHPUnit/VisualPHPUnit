<?php

namespace app\controller;

class Home extends \app\core\Controller {

    // GET
    public function index($request) {
        /*
        if ( $request->is('get') ) {
            return array();
        }
         */

        $vpu = new \app\lib\VPU();
        $results = $vpu->run(array(
            '/srv/http/pu/app/test/PUTest.php',
            '/srv/http/pu/app/test/PUTest2.php',
            '/srv/http/pu/app/test/SkippedTest.php',
            '/srv/http/pu/app/test/IncompleteTest.php',
            '/srv/http/pu/app/test/DateTest.php'
        ));
        $results = $vpu->compile_suites($results);
        $suites = $results['suites'];
        $stats = $results['stats'];

        $test_directory = str_replace(
            '\\', '/', realpath(\app\lib\Library::retrieve('test_directory'))
        );

        return compact('suites', 'stats', 'test_directory');
    }

}

?>
