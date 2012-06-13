<?php

namespace app\controller;

class Home extends \app\core\Controller {

    // GET
    public function index($request) {
        $test_directory = str_replace(
            '\\', '/', realpath(\app\lib\Library::retrieve('test_directory'))
        );

        if ( $request->is('get') ) {
            $suites = array();
            $stats = array();
            return compact(
                'stats',
                'suites',
                'test_directory'
            );
        }

        $tests = explode('|', $request->data['test_files']);

        $vpu = new \app\lib\VPU();
        $results = $vpu->run($tests);
        $results = $vpu->compile_suites($results);
        $suites = $results['suites'];
        $stats = $results['stats'];

        $to_view = compact('suites', 'stats');

        $notifications = array();

        if ( $request->data['create_snapshots'] ) {
            $filename = realpath(
                \app\lib\Library::retrieve('snapshot_directory')
            ) . '/' . date('Y-m-d_G-i') . '.html';

            $contents = $this->render_html('index', $to_view);

            $handle = @fopen($filename, 'a');
            if ( !$handle ) {
                $notifications[] = array(
                    'type'    => 'failed',
                    'message' => 'Could not create a snapshot.  Please ensure '
                        . 'that the <code>snapshot_directory</code> in '
                        . '<code>app/config/bootstrap.php</code> exists and '
                        . 'has the proper permissions.'
                );
            } else {
                fwrite($handle, $contents);
                fclose($handle);
                $notifications[] = array(
                    'type'    => 'succeeded',
                    'message' => 'Snapshot created at <code>' . $filename
                        . '</code>.'
                );

            }
        }

        return $to_view + compact('notifications');
    }

}

?>
