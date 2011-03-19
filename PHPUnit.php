<?php

require 'PHPUnit/Autoload.php';
require 'PHPUnit/Util/Log/JSON.php';

class PHPUnit 
{
    private $_test_cases = array();
	
    public function __construct($test_dir=null)
    {
        if ( !is_null($test_dir) )
        {
            $this->_set_dir($test_dir);
        }
    }

    private function _build_suite($suite)
    {
        $suite['expand'] = ( $suite['status'] == 'failure' ) ? '-' : '+';
        $suite['display'] = ( $suite['status'] == 'failure' ) ? 'show' : 'hide';
        $suite['time'] = 'Executed in ' . $suite['time'] . ' seconds.'; 

        ob_start(); 
        include 'ui/suite.html';
        $suite_content = ob_get_contents(); 
        ob_end_clean();
        return $suite_content;
    }

    private function _build_test($test, $variables, $trace, $separator)
    {
        if ( $variables['message'] && $test['status'] === 'failure' ) 
        {
            $test['expand'] = '-';
            $test['display'] = 'show';
        }
                
        ob_start(); 
        include 'ui/test.html';
        $test_content = ob_get_contents(); 
        ob_end_clean();
        return $test_content;
    }

    private function _get_message($message)
    {
        if ( !$message ) 
        {
            return '';
        }

        $first = substr($message, 0, strpos($message, 'Failed'));
        $message_rest = str_replace($first, '', $message);
        $first = '<strong>' . $first . '</strong><br />';
        if ( $message_rest ) 
        {
            $message = $first . $message_rest;
        } 
        else
        {
            $message = $first;
        }
        
        return $message;
    }

    private function _get_status($status)
    {
        switch ( $status )
        {
            case 'pass':
                $status = 'success';
                break;
            case 'error': 
                if ( stripos($event['message'], 'skipped') !== false )
                {
                    $status = 'skipped';
                }
                elseif ( stripos($event['message'], 'incomplete') !== false )
                {
                    $status = 'incomplete';
                }
                else
                {
                    $status = 'failure';
                }
                break;
            case 'fail':
                $status = 'failure';
                break;
            default:
                $status = '';
                break;
        }

        return $status;
    }

    private function _get_trace($trace)
    {
        if ( !$trace ) 
        {
            return '';
        }

        $new_trace = array();
        foreach ( $trace as $arr ) 
        {
            if ( (stripos($arr['file'], 'phpunit.php') === false) &&
                // TODO: THIS LINE - Main/index.php will have to change
                    (stripos($arr['file'], 'Main/index.php') === false) &&
                    ((!isset($arr['class'])) || (stripos($arr['class'], 'phpunit') === false) )) 
            {
                $new_trace[] = $arr;
            }
        }
        
        if ( !empty($new_trace) ) 
        {
            ob_start();
            print_r($new_trace);
            $trace = trim(ob_get_contents());
            ob_end_clean();
        } 
        else 
        {
            $trace = '';
        }

        return $trace;
    }

    private function _load_tests($tests=null)
    {
        if ( is_null($tests) ) 
        {
            $tests = $this->_test_cases;
        } 
        elseif ( is_string($name) ) 
        {
            $tests = array($tests);
        }

        $loaded_classes = get_declared_classes();

        foreach ( $tests as $test ) 
        {
            require $test;
        }

        // Only return the classes that were just loaded
        return array_diff(get_declared_classes(), $loaded_classes); 
    }

    private function _parse_output($pu_output)
    {
        $results = '';
        $json_elements = $this->_pull($pu_output);
        foreach ( $json_elements as $elem ) 
        {
            $elem = '{' . $elem . '}';
            $pu_output = $this->_push($elem, '|||', $pu_output);
            $results .= $elem . ',';
        }

        $results = '[' . rtrim($results, ',') . ']';
        $results = str_replace('\n', "", $results);
        $results = str_replace('&quot;', '"', $results);
        $results = json_decode($results, true);
        
        $pu_output = explode('|||', $pu_output);
        foreach ( $pu_output as $key=>$data ) 
        {
            if ( isset($results[$key]) ) 
            {
                $results[$key]['collected'] = $data;
            }
        }

        // Remove the first element
        if ( is_array($results) )
        {
            array_shift($results);
        }

        return $results;
    }

    private function _pull($str) 
    {
        $tags = array();
        $nest = 0;
        $start_mark = 0;
        
        $length = strlen($str);
        for ( $i=0; $i < $length; $i++ ) 
        { 
            $char = $str{$i};
            
            if ( $char == '{' ) 
            {
                $nest++;
                if ( $nest == 1 ) 
                {
                    $start_mark = $i;
                }
            }
            elseif ( $char == '}' ) 
            {
                if ( $nest == 1 ) 
                {
                    $tags[] = substr($str, $start_mark + 1, $i - $start_mark - 1);
                    $start_mark = $i;
                }
                $nest--;
            }
        }
        
        if ( $nest !== 0 ) 
        {
            // TODO: Throw exception
            die("Unable to parse JSON response from PHPUnit.");
        }
        
        return $tags;
    }

    private function _push($old, $new, $subject) 
    {
        $pos = strpos($subject, $old);
        
        if ( $pos !== false )
        {
            return substr_replace($subject, $new, $pos, strlen($old));
        }
        else
        {
            // TODO: Throw exception
            die("Cannot find tag to replace (old: " . $old . ", new: " . htmlspecialchars($new) . ").");
        }
    }
	
    public function run($tests=null) 
    {
        $suite = new PHPUnit_Framework_TestSuite();

        $loaded_tests = $this->_load_tests($tests);
        foreach ( $loaded_tests as $test ) 
        {
            if ( (stripos($test, TEST_FILENAME) !== false) && (stripos($test, 'PHPUnit_') === false) ) 
            {
                $suite->addTestSuite($test);
            }
        }

        $result = new PHPUnit_Framework_TestResult;

        $result->addListener(new PHPUnit_Util_Log_JSON);
        
        ob_start();
        $suite->run($result);
        $results = ob_get_contents();
        ob_end_clean();
        
        return $results;
    }

    private function _set_dir($test_dir)
    {
        $test_dir = realpath($test_dir);
        if ( !is_dir($test_dir) ) 
        {
            throw new Exception("$test_dir is not a dir", 1);
        }

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($test_dir), RecursiveIteratorIterator::SELF_FIRST);
                        
        $pattern = '/' . TEST_FILENAME . '/i';
        while ( $it->valid() ) 
        {
            $filename = $it->getSubPathName();
            if ( !$it->isDot() && preg_match($pattern, $filename) ) 
            {
                $this->_test_cases[] = $filename;
            }

            $it->next();
        }
    }

    public function to_HTML($pu_output) 
    {
        $results = $this->_parse_output($pu_output);    
        if ( !is_array($results) )
        {
            return '';
        }

        $final = '';
        $suite = $test = $variables = $trace = $separator = array();
        
        foreach ( $results as $key=>$event ) 
        {
            if ( $event['event'] === 'suiteStart' ) 
            {
                if ( isset($suite['tests']) )
                {
                    $final .= $this->_build_suite($suite);
                    $suite = $test = $variables = $trace = $separator = array();
                }

                $suite['status'] = 'success';
                $suite['name'] = $event['suite'];
                $suite['tests'] = '';
                $suite['time'] = 0;
            } 
            elseif ( $event['event'] == 'test' ) 
            {
                $test['status'] = $this->_get_status($event['status']);
                $test['expand'] = ( $test['status'] == 'fail' ) ? '-' : '+';
                $test['display'] = ( $test['status'] == 'fail' ) ? 'show' : 'hide';

                if ( $test['status'] === 'incomplete' && $suite['status'] !== 'failure' && $suite['status'] !== 'skipped' ) 
                {
                    $suite['status'] = 'incomplete';
                } 
                elseif ( $test['status'] === 'skipped' && $suite['status'] !== 'failure' ) 
                {
                    $suite['status'] = 'skipped';
                } 
                elseif ( $test['status'] === 'failure' ) 
                {
                    $suite['status'] = 'failure';
                }
                
                $test['name'] = substr($event['test'], strpos($event['test'], '::') + 2);
                $test['message'] = $this->_get_message($event['message']); 
                $test['message'] .= '<br /><br />Executed in ' . $event['time'] . ' seconds.';
                $suite['time'] += $event['time'];

                $variables['message'] = ( isset($event['collected']) ) ? trim($event['collected']) : '';
                $variables['display'] = ( $variables['message'] ) ? 'show' : 'hide';

                $trace['message'] = $this->_get_trace($event['trace']);
                $trace['display'] = ( $trace['message'] ) ? 'show' : 'hide';
                
                $separator['display'] = ( isset($results[$key + 1]) && $results[$key +1 ]['event'] !== 'suiteStart' ); 

                $suite['tests'] .= $this->_build_test($test, $variables, $trace, $separator); 
            }	
                        
        }

        if ( isset($suite['tests']) )
        {
            $final .= $this->_build_suite($suite);
        }
        
        return $final;
    }
	
}

?>
