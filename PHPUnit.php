<?php

require 'PHPUnit/Framework.php';
require 'PHPUnit/Util/Log/JSON.php';

class PHPUnit 
{
    private $_test_dir;
    private $_test_cases = array();
    private $_results;
	
    public function __construct($test_dir)
    {
        if ( is_dir(realpath($test_dir)) ) 
        {
            $this->_test_dir = realpath($test_dir);
        } 
        else 
        {
            throw new Exception("$test_dir is not a dir", 1);
        }

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->_testDir), RecursiveIteratorIterator::SELF_FIRST);
                        
        $pattern = '/' . TEST_FILENAME . '$/i';
        while ( $it->valid() ) 
        {
            $filename = $it->getSubPathName();
            if ( !$it->isDot() && preg_match($pattern, $filename) ) 
            {
                $this->_test_cases[basename($filename)] = $filename;
            }

            $it->next();
        }

    }
	
    public function run($tests=null) 
    {
        if ( is_null($tests) ) 
        {
            $tests = $this->test_cases;
        } 
        elseif ( is_string($name) ) 
        {
            $tests = array($tests);
        }

        foreach ( $tests as $test ) 
        {
            require($test);
        }

        $suite = new PHPUnit_Framework_TestSuite();
        foreach ( get_declared_classes() as $class ) 
        {
            if ( (stripos($class, TEST_FILENAME) !== false) && (strpos($class, 'PHPUnit_') === false) ) 
            {
                $suite->addTestSuite($class);
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
	
    public function toHTML($pu_result) 
    {
        $results = array();
        
        $json_elements = $this->_pull($pu_result);
        
        foreach ( $json_elements as $elem ) 
        {
            $elem = '{' . $elem . '}';
            $pu_result = $this->_push($elem, '|||', $pu_result);
            $results[] = $elem;
        }

        $results = implode(",", $results);
        $results = '[' . $results . ']';
        
        $results = str_replace('\n', "", $results);
        $results = str_replace('&quot;', '"', $results);
        
        $pu_result = explode('|||', $pu_result);
        
        $results = json_decode($results, true);
        
        // map collected data to results
        foreach ( $pu_result as $key=>$data ) 
        {
            if ( isset($results[$key]) ) 
            {
                $results[$key]['collected'] = $data;
            }
        }
            
        $out = array();
        
        $started = null;
        $test = array();
                        
        foreach ( $results as $key=>$event ) 
        {
            if ( $event['event'] == 'suiteStart' ) 
            {
                if ( !is_null($started) ) 
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
            
                    $out[] = $left . implode('', $test) . $right;
                }	
                $started = $event;
                $test = array();
                $suite_success = 'success';
            } 
            elseif ( $event['event'] == 'test' ) 
            {
                $status = '';
                switch ( $event['status'] )
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
                }
                
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
                
                $temp = explode('::', $event['test']);
                $name = end($temp);
                $message = ( isset($event['message']) ) ? $event['message'] : '';
                $expand = ( $status == 'fail' ) ? '-' : '+';
                $display = ( $status == 'fail' ) ? 'show' : 'hide';
                $trace = ( isset($event['trace']) ) ? $event['trace'] : '';
                $show_trace = ($trace) ? 'show' : 'hide';
                
                if ( $message ) 
                {
                    $message_pieces = explode('Failed', $message);
                    $first = array_shift($message_pieces);
                    $message_rest = implode('Failed', $message_pieces);
                    $message_rest = str_replace($first, '', $message_rest);
                    $first = '<strong>'.$first.'</strong><br />';
                    if ( $message_rest ) 
                    {
                        $message = $first . 'Failed' . $message_rest;
                    } 
                    else
                    {
                        $message = $first;
                    }
                }
                
                if ( $trace ) 
                {
                    $new_trace = array();
                    foreach ( $trace as $arr ) 
                    {
                        if ( (stristr($arr['file'], 'phpunit.php') === false) &&
                             (stristr($arr['file'], 'Main/index.php') === false) &&
                             ((!isset($arr['class'])) || (stristr($arr['class'], 'phpunit') === false) )
                        ) 
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
                        $show_trace = 'hide';
                    }
                    
                }
                
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
                                    <pre>' . trim((string)$trace) . '</pre>
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
            
            $out[] = $left . implode('', $test) . $right;
        }
        
        // Deal with last test case
        
        $out = implode('', $out);
        
        return $out;
    }
	
    // TODO: Fix
    private function _push($old, $new, $subject) 
    {
        $pos = strpos($subject,$old);
        
        if ($pos !== false)
            return substr_replace($subject,$new,$pos,strlen($old));
        else
            throw new Exception("Cannot find tag to replace (old: $old, new: ".htmlspecialchars($new).")", 1);
    }
	
    // TODO: Fix
    private function _pull($str) 
    {
        $start = '{'; 
        $end = '}';

        $tags = array();
        $nest = -1;
        $start_mark = 0;
        
        for ( $i=0; $i < strlen($str); $i++ ) 
        { 
            $start_substr = substr($str, $i, strlen($start));
            $end_substr = substr($str, $i, strlen($end));
            
            if($start_substr == $start) {
                    $nest++;
                    if($nest == 0) {
                            $start_mark = $i;
                    }
            }
            elseif($end_substr == $end) {
                    if($nest == 0) {
                            // $tags[] = substr($str, $start_mark, $i+strlen($end));
                            $tags[] = substr($str, $start_mark + strlen($start), $i - $start_mark - strlen($start));
                            $start_mark = $i;
                    }
                    $nest--;
            }
        }
        
        if($nest != -1) {
            throw new Exception("Unable to parse - probably forgot a curly!", 1);
        }
        
        return $tags;
    }
	
}

?>
