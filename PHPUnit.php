<?php

require '/usr/lib/php/PHPUnit/Autoload.php';
require '/usr/lib/php/PHPUnit/Util/Log/JSON.php';

class PHPUnit 
{
    private $_test_dir;
    private $_test_cases = array();
    private $_results;
	
    public function __construct($test_dir=null)
    {
        if ( !is_null($test_dir) )
        {
            $this->_set_dir($test_dir);
        }
    }
    
    private function _set_dir($test_dir)
    {
        if ( is_dir(realpath($test_dir)) ) 
        {
            $this->_test_dir = realpath($test_dir);
        } 
        else 
        {
            throw new Exception("$test_dir is not a dir", 1);
        }

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->_test_dir), RecursiveIteratorIterator::SELF_FIRST);
                        
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
            $trace = ob_get_contents();
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
	
    public function toHTML($pu_output) 
    {
        $results = array();
        
        $json_elements = $this->_pull($pu_output);
        
        foreach ( $json_elements as $elem ) 
        {
            $elem = '{' . $elem . '}';
            $pu_output = $this->_push($elem, '|||', $pu_output);
            $results[] = $elem;
        }

        $results = implode(",", $results);
        $results = '[' . $results . ']';
        
        $results = str_replace('\n', "", $results);
        $results = str_replace('&quot;', '"', $results);
        
        $pu_output = explode('|||', $pu_output);
        
        $results = json_decode($results, true);
        
        // map collected data to results
        foreach ( $pu_output as $key=>$data ) 
        {
            if ( isset($results[$key]) ) 
            {
                $results[$key]['collected'] = $data;
            }
        }
            
        $final = '';
        $started = null;
        $test = array();

        // Remove the first element
        array_shift($results);
                        
        foreach ( $results as $event ) 
        {
        echo '<pre>';
        print_r($event);
        echo '</pre><br />';
            if ( $event['event'] === 'suiteStart' ) 
            {
                $suite_expand = ( $suite_success == 'failure' ) ? '-' : '+';
                $suite_display = ( $suite_success == 'failure' ) ? 'show' : 'hide';

                $left = '<div class="box rounded">
                        <div class="testsuite ' . $suite_success . '">
                            <div class="light rounded"></div>
                            <div class="name">' . $started['suite'] . '</div>
                            <div class="stats"></div>
                            <div class="expand button">' . $suite_expand . '</div>
                        </div>
                        <div class="more ' . $suite_display . '">
                            <hr class = "big" />
                        ';
        
                $right = '</div></div>
                         ';
        
                $final .= $left . implode('', $test) . $right;

                $started = $event;
                $test = array();
                $suite_success = 'success';
            } 
            elseif ( $event['event'] == 'test' ) 
            {
                $status = $this->_get_status($event['status']);
                $expand = ( $status == 'fail' ) ? '-' : '+';
                $display = ( $status == 'fail' ) ? 'show' : 'hide';

                if ( $status === 'incomplete' && $suite_success !== 'failure' && $suite_success !== 'skipped' ) 
                {
                    $suite_success = 'incomplete';
                } 
                elseif ( $status === 'skipped' && $suite_success !== 'failure' ) 
                {
                    $suite_success = 'skipped';
                } 
                elseif ( $status === 'failure' ) 
                {
                    $suite_success = 'failure';
                }
                
                $name = substr($event['test'], strpos($event['test'], '::') + 2);
                $message = $this->_get_message($event['message']); 
                $trace = $this->_get_trace($event['trace']);
                $show_trace = ( $trace ) ? 'show' : 'hide';
                
                $variables = ( isset($event['collected']) ) ? $event['collected'] : '';
                $show_variables = ( $variables ) ? 'show' : 'hide';
                
                $test[] = '<div class="test '. $status . '">
                        <div class="light rounded"></div>
                        <div class="name">' . $name . '</div>
                        <div class="stats">' . $message . '</div>';
                                                        
                if ( $variables && $status == 'failure' ) 
                {
                    $test[] = '<div class="expand button">-</div>';
                    $display = 'show';
                }
                elseif ( $variables || $trace ) 
                {
                    $test[] = '<div class="expand button">' . $expand . '</div>';
                }	
                
                $test[] = '<div class="more test ' . $display . '">
                                <div class="variables rounded ' . $show_variables . '">
                                    <pre>' . trim($variables) . '</pre>
                                </div>
                                <div class="stacktrace rounded ' . $show_trace . '">
                                    <pre>' . trim($trace) . '</pre>
                                </div>
                        </div>
                </div>
                ';
                
                if ( isset($results[$i+1]) && $results[$i+1]['event'] !== 'suiteStart' ) 
                {
                    $test[] = '<hr class = "small" />';
                }
            }	
                        
        }
        
        if ( isset($started) ) 
        {
            // Close out last case
            $suite_expand = ( $suite_success == 'failure' ) ? '-' : '+';
            $suite_display = ( $suite_success == 'failure' ) ? 'show' : 'hide';
            
            $left = '<div class="box rounded">
                    <div class="testsuite ' . $suite_success . '">
                        <div class="light rounded"></div>
                        <div class="name">' . $started['suite'] . '</div>
                        <div class="stats"></div>
                        <div class="expand button">' . $suite_expand . '</div>
                    </div>
                    <div class="more ' . $suite_display . '">
                        <hr class = "big" />
                    ';
            
            $right = '</div></div>';
            
            $final .= $left . implode('', $test) . $right;
        }
        
        return $final;
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
	
}

?>
