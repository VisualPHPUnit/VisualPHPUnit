<?php

namespace app\lib;

class VPU {

   /**
    *  The sandboxed exceptions.
    *
    *  @var string
    *  @access protected
    */
    protected $_exceptions = '';

   /**
    * Adds percentage statistics to the provided statistics.
    *
    * @param array $statistics    The statistics.
    * @access protected
    * @return array
    */
    protected function _add_percentages($statistics) {
        $results = array();
        foreach ( $statistics as $name => $stats ) {
            $results[$name] = $stats;
            foreach ( $stats as $key => $value ) {
                if ( $key == 'total' ) {
                    continue;
                }
                // Avoid divide by zero error
                if ( $stats['total'] ) {
                    $results[$name]['percent_' . $key] =
                        round($stats[$key] / $stats['total'] * 100, 1);
                } else {
                    $results[$name]['percent_' . $key] = 0;
                }
            }
        }

        return $results;
    }

   /**
    * Returns the class name without the namespace.
    *
    * @param string $class    The class name.
    * @access protected
    * @return string
    */
    protected function _classname_only($class) {
        $name = explode('\\', $class);
        return end($name);
    }

   /**
    *  Organizes the output from PHPUnit into a more manageable array
    *  of suites and statistics.
    *
    *  @param string $pu_output        The JSON output from PHPUnit.
    *  @access public
    *  @return array
    */
    public function compile_suites($pu_output) {
        $results = $this->_parse_output($pu_output);

        $collection = array();
        $statistics = array(
            'suites' => array(
                'succeeded'  => 0,
                'skipped'    => 0,
                'incomplete' => 0,
                'failed'     => 0,
                'total'      => 0
            )
        );
        $statistics['tests'] = $statistics['suites'];
        foreach ( $results as $result ) {
            if ( $result['event'] != 'test' ) {
                continue;
            }

            $suite_name = $this->_classname_only($result['suite']);

            if ( !isset($collection[$suite_name]) ) {
                $collection[$suite_name] = array(
                    'tests'  => array(),
                    'name'   => $suite_name,
                    'status' => 'succeeded',
                    'time'   => 0
                );
            }
            $result = $this->_format_test_results($result);
            $collection[$suite_name]['tests'][] = $result;
            $collection[$suite_name]['status'] = $this->_get_suite_status(
                $result['status'], $collection[$suite_name]['status']
            );
            $collection[$suite_name]['time'] += $result['time'];
            $statistics['tests'][$result['status']] += 1;
            $statistics['tests']['total'] += 1;
        }

        foreach ( $collection as $suite ) {
            $statistics['suites'][$suite['status']] += 1;
            $statistics['suites']['total'] += 1;
        }

        $final = array(
            'suites' => $collection,
            'stats'  => $this->_add_percentages($statistics)
        );

        return $final;
    }

   /**
    *  Creates an HTML snapshot of the test results.
    *
    *  @param string $data            The data to be written.
    *  @param string $dir             The directory in which to store the snapshot.
    *  @access public
    *  @return void
    */
    public function create_snapshot($data, $dir) {
        if ( $dir{strlen($dir) - 1} !== '/' ) {
            $dir .= '/';
        }
        $filename = $dir . date('Y-m-d_G-i') . '.html';
        $this->_write_file($filename, $data);
        // TODO: Add a try/catch for this
        chmod($filename, 0777);
    }

   /**
    *  Erases the contents of a file.
    *
    *  @param string $filename        The file to be emptied.
    *  @access protected
    *  @return void
    */
    protected function _empty_file($filename) {
        $this->_write_file($filename, '', 'w');
    }

   /**
    * Normalizes the test results.
    *
    * @param array $test_results    The parsed test results.
    * @access protected
    * @return string
    */
    protected function _format_test_results($test_results) {
        $status = $this->_get_test_status(
            $test_results['status'], $test_results['message']
        );
        $name = substr(
            $test_results['test'], strpos($test_results['test'], '::') + 2
        );
        $time = $test_results['time'];
        $message = $test_results['message'];
        $output = ( isset($test_results['output']) )
            ? trim($test_results['output'])
            : '';
        $trace = $this->_get_trace($test_results['trace']);

        return compact(
            'status',
            'name',
            'time',
            'message',
            'output',
            'trace'
        );
    }

   /**
    *  Retrieves all of the formatted errors.
    *
    *  @access protected
    *  @return string
    */
    protected function _get_errors() {
        global $sandbox_filename;
        $errors = file_get_contents($sandbox_filename);
        $this->_empty_file($sandbox_filename);
        return $errors;
    }

   /**
    * Determines the overall suite status based on the current status
    * of the suite and the status of a single test.
    *
    * @param string $test_status     The status of the test.
    * @param string $suite_status    The current status of the suite.
    * @access protected
    * @return string
    */
    protected function _get_suite_status($test_status, $suite_status) {
        if (
            $test_status === 'incomplete' && $suite_status !== 'failed'
            && $suite_status !== 'skipped'
        ) {
            return 'incomplete';
        }
        if ( $test_status === 'skipped' && $suite_status !== 'failed' ) {
            return 'skipped';
        }
        if ( $test_status === 'failed' ) {
            return 'failed';
        }
        return $suite_status;
    }

   /**
    * Retrieves the status from a PHPUnit test result.
    *
    * @param string $status     The status supplied by VPU's transformed JSON.
    * @param string $message    The message supplied by VPU's transformed JSON.
    * @access protected
    * @return string
    */
    protected function _get_test_status($status, $message) {
        switch ( $status ) {
            case 'pass':
                return 'succeeded';
            case 'error':
                if ( stripos($message, 'skipped') !== false ) {
                    return 'skipped';
                }
                if ( stripos($message, 'incomplete') !== false ) {
                    return 'incomplete';
                }
                return 'failed';
            case 'fail':
                return 'failed';
            default:
                return '';
        }
    }

   /**
    * Filters the stack trace from a PHPUnit test result to exclude the VPU's
    * trace.
    *
    * @param string $stack    The stack trace.
    * @access protected
    * @return string
    */
    protected function _get_trace($stack) {
        if ( !$stack ) {
            return '';
        }

        ob_start();
        print_r(array_slice($stack, 0, -6));
        $trace = trim(ob_get_contents());
        ob_end_clean();

        return $trace;
    }

   /**
    *  Serves as the error handler.  Formats the errors, and then writes them to the sandbox file.
    *
    *  @param int $err_no            The level of the error raised.
    *  @param string $err_str        The error message.
    *  @param string $err_file       The file in which the error was raised.
    *  @param int $err_line          The line number at which the error was raised.
    *  @access public
    *  @return bool
    */
    public function handle_errors($err_no, $err_str, $err_file, $err_line) {
        global $sandbox_ignore, $sandbox_filename;

        if ( $sandbox_ignore != '' ) {
            $ignore = explode('|', $sandbox_ignore);
            $transform_to_constant = function($value) { return constant($value); };
            $ignore = array_map($transform_to_constant, $ignore);
            if ( in_array($err_no, $ignore) ) {
                return true;
            }
        }

        $error = array();
        switch ( $err_no ) {
            case E_NOTICE:
                $error['type'] = 'Notice';
                break;
            case E_WARNING:
                $error['type'] = 'Warning';
                break;
            case E_ERROR:
                $error['type'] = 'Error';
                break;
            case E_PARSE:
                $error['type'] = 'Parse';
                break;
            case E_STRICT:
                $error['type'] = 'Strict';
                break;
            default:
                $error['type'] = 'Unknown';
                break;
        }
        $error['message'] = $err_str;
        $error['line'] = $err_line;
        $error['file'] = $err_file;
        ob_start();
        include 'ui/error.html';
        $this->_write_file($sandbox_filename, ob_get_contents());
        ob_end_clean();
        return true;
    }

   /**
    *  Formats exceptions for sandbox use.
    *
    *  @param Exception $exception            The thrown exception.
    *  @access protected
    *  @return void
    */
    protected function _handle_exception($exception) {
        $error = array(
            'type'    => 'Exception',
            'message' => $exception->getMessage(),
            'line'    => $exception->getLine(),
            'file'    => $exception->getFile()
        );

        ob_start();
        include 'ui/error.html';
        $this->_exceptions .= ob_get_contents();
        ob_end_clean();
    }

   /**
    * Parses and formats the JSON output from PHPUnit into an associative array.
    *
    * @param string $pu_output    The JSON output from PHPUnit.
    * @access protected
    * @return array
    */
    protected function _parse_output($pu_output) {
        $results = '';
        foreach ( $this->_convert_json($pu_output) as $elem ) {
            $elem = '{' . $elem . '}';
            $pos = strpos($pu_output, $elem);
            $pu_output = substr_replace($pu_output, '|||', $pos, strlen($elem));
            $results .= $elem . ',';
        }

        $results = '[' . rtrim($results, ',') . ']';
        $results = str_replace('&quot;', '"', $results);

        $results = json_decode($results, true);

        // For PHPUnit 3.5.x, which doesn't include test output in the JSON
        $pu_output = explode('|||', $pu_output);
        foreach ( $pu_output as $key => $data ) {
            if ( $data ) {
                $results[$key]['output'] = $data;
            }
        }

        return $results;
    }

   /**
    * Retrieves the files from any supplied directories, and filters
    * the list of tests by ensuring that the files exist and are PHP files.
    *
    * @param array $tests    The directories/filenames containing the tests to
    *                        be run through PHPUnit.
    * @access protected
    * @return array
    */
    protected function _parse_tests($tests) {
        $collection = array();

        foreach ( $tests as $test )  {
            if ( is_dir($test) ) {
                $it = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator(realpath($test)),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );
                while ( $it->valid() ) {
                    $ext = strtolower(pathinfo($it->key(), PATHINFO_EXTENSION));
                    if ( !$it->isDot() && $ext == 'php' ) {
                        $collection[] = $it->key();
                    }

                    $it->next();
                }
                continue;
            }

            $ext = strtolower(pathinfo($test, PATHINFO_EXTENSION));
            if ( file_exists($test) && $ext == 'php' )  {
                $collection[] = $test;
            }
        }
        // Avoid returning duplicates
        return array_keys(array_flip($collection));
    }

   /**
    * Converts the first nested layer of PHPUnit-generated JSON to an
    * associative array.
    *
    * @param string $str    The JSON output from PHPUnit.
    * @access protected
    * @return array
    */
    protected function _convert_json($str) {
        $tags = array();
        $nest = 0;
        $start_mark = 0;

        $length = strlen($str);
        for ( $i = 0; $i < $length; $i++ ) {
            $char = $str{$i};

            if ( $char == '{' ) {
                // Ensure we're only adding events to the array
                if (
                    $nest == 0 && substr($str, $i, 18) != '{&quot;event&quot;'
                ) {
                    continue;
                }

                $nest++;
                if ( $nest == 1 ) {
                    $start_mark = $i;
                }
            } elseif ( $char == '}' && $nest > 0 ) {
                if ( $nest == 1 ) {
                    $tags[] = substr(
                        $str, $start_mark + 1, $i - $start_mark - 1
                    );
                    $start_mark = $i;
                }
                $nest--;
            }
        }

        return $tags;
    }

   /**
    * Runs supplied tests through PHPUnit.
    *
    * @param array $tests    The directories/filenames containing the tests
    *                        to be run through PHPUnit.
    * @access public
    * @return string
    */
    public function run($tests) {
        $suite = new \PHPUnit_Framework_TestSuite();

        $tests = $this->_parse_tests($tests);
        $original_classes = get_declared_classes();
        foreach ( $tests as $test ) {
            require $test;
        }
        $new_classes = get_declared_classes();
        $tests = array_diff($new_classes, $original_classes);
        foreach ( $tests as $test ) {
            $classname = $this->_classname_only($test);
            if (
                $classname == 'PHPUnit_Framework_TestCase'
                || stripos($classname, 'test') === false
            ) {
                continue;
            }

            $suite->addTestSuite($test);
        }

        $result = new \PHPUnit_Framework_TestResult;
        $result->addListener(new \PHPUnit_Util_Log_JSON);

        // We need to temporarily turn off html_errors to ensure correct
        // parsing of test debug output
        $html_errors = ini_get('html_errors');
        ini_set('html_errors', 0);

        ob_start();
        $suite->run($result);
        $results = ob_get_contents();
        ob_end_clean();

        ini_set('html_errors', $html_errors);

        return $results;
    }

   /**
    *  Saves the statistics to a database.
    *
    *  @param string $results     The JSON output from PHPUnit.
    *  @param object $db          The database handler.
    *  @access public
    *  @return bool
    */
    public function save_results($results, $db) {
        $results = $this->_compile_suites($results);
        $now = date('Y-m-d H:i:s');
        foreach ( $results['stats'] as $key => $result ) {
            $data = array(
                'run_date'   => $now,
                'failed'     => $result['failed'],
                'incomplete' => $result['incomplete'],
                'skipped'    => $result['skipped'],
                'succeeded'  => $result['succeeded']
            );
            $table = ucfirst(rtrim($key, 's')) . 'Result';
            $db->insert($table, $data);
        }
        return true;
    }

   /**
    * Outputs suite and statistics data in HTML.
    *
    * @param string $results         The JSON output from PHPUnit.
    * @param bool $sandbox_errors    Whether or not to sandbox errors.
    * @access public
    * @return string
    */
    public function to_HTML($results, $sandbox_errors) {
        $collection = $this->_compile_suites($results);
        $final = '';
        $final .= $this->_build_stats($collection['stats']);

        if ( $sandbox_errors ) {
            $final .= $this->_exceptions . $this->_get_errors();
        }

        return $final;
    }

   /**
    *  Writes data to a file.
    *
    *  @param string $filename        The name of the file.
    *  @param string $data            The data to be written.
    *  @param string $mode            The type of access to be granted to the file handle.
    *  @access protected
    *  @return string
    */
    protected function _write_file($filename, $data, $mode='a') {
        try {
            $handle = @fopen($filename, $mode);
            if ( !$handle ) {
                throw new \Exception('Could not open ' . $filename . ' for writing.  Check the location and permissions of the file and try again.');
            }

            fwrite($handle, $data);
            fclose($handle);
            return true;
        } catch (Exception $e) {
            $this->_handle_exception($e);
            return false;
        }
    }

}

?>
