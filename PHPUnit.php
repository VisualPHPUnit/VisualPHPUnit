<?php

/** 
* Short Description
*
* Long Description
* @package PHPUnit
* @author Matt Mueller
*/


set_include_path(dirname(__FILE__));
require_once('PHPUnit/Framework.php');


class PHPUnit 
{
	public $testDir;
	public $testCases = array();
	public $results;
	public $suite;
	
	function __construct($testDir, $suite = '')
	{
		if(is_dir(realpath($testDir))) {
			$this->testDir = realpath($testDir);
		} else {
			throw new Exception("$testDir is not a dir", 1);
		}
		
		$path = realpath($this->testDir);
		$this->suite = $suite;

		$objects = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator($path), 
					RecursiveIteratorIterator::SELF_FIRST
				   );
				
		foreach($objects as $name){
			if(strstr($name, 'TestCase.php') !== false) {
				$parts = explode('/', $name);
				$filename = end($parts);
				$filename = strtolower(str_replace('TestCase.php', '', $filename));
				$this->testCases[$filename] = (string) $name;
			}
		}
				
	}
	
	public function run($name = null) {
		$tests = array();
		if(!isset($name)) {
			$tests = $this->testCases;
		} elseif(is_array($name)) {
			// run specified tests
		} elseif(is_string($name)) {
			// run test
			$tests[] = $name;
		}


		foreach ($tests as $name => $mixed) {
			if(is_numeric($name)) {
				$name = $mixed;
			}
			
			$name = strtolower($name);
			$path = $this->testCases[$name];

			require_once($path);
		}

		$testClasses = array();
		
		foreach(get_declared_classes() as $class) {
			if((stristr($class, 'Test') !== false) && (strpos($class, 'PHPUnit_') === false)) {
				$testClasses[] = $class;
			}
		}

		$suite = new PHPUnit_Framework_TestSuite();
		
		
		foreach ($testClasses as $class) {
			if($this->suite) {
				if(strcasecmp($this->suite, $class) == 0) {
					$suite->addTestSuite($class);
				}
			} else {
				$suite->addTestSuite($class);
			}
			
		}
		
		$result = new PHPUnit_Framework_TestResult;
		require_once 'PHPUnit/Util/Log/JSON.php';

        $result->addListener(
          new PHPUnit_Util_Log_JSON
        );
		
		$this->suite = $suite;


		ob_start();
		$suite->run($result);
		$results = ob_get_contents();
		ob_end_clean();
		
		return $results;

	}
	
	public static function toHTML($result) {

		$results = array();
		$collectedData = $result;
		
		$jsonElements = self::pull($result);
		
		foreach ($jsonElements as $elem) {
			$elem = '{'.$elem.'}';
			$collectedData = self::push($elem, '|||', $collectedData);
			$results[] = $elem;
		}
		$results = implode(",", $results);
		$results = '['.$results.']';
		
		$results = str_replace('\n', "", $results);
		$results = str_replace('&quot;', '"', $results);
		
		$collectedData = explode('|||', $collectedData);
		
		
		$results = json_decode($results, true);
		
		// map collected data to results
		foreach ($collectedData as $i => $data) {
			
			if(isset($results[$i])) {
				$results[$i]['collected'] = $data;
			}
		}
		
		$out = array();
		
		// Maybe do something later with first result (like header)

		$header = array_shift($results);
		$started = null;
		$test = array();
				
		foreach ($results as $i => $event) {
			if($event['event'] == 'suiteStart') {
				if(isset($started)) {
					// Close out last case
					$suite_expand = ($suite_success == 'failure') ? '-' : '+';
					$suite_display = ($suite_success == 'failure') ? 'show' : 'hide';

					$left = '<div class="box rounded">
						<div class="testsuite '.$suite_success.'">
							<div class="light rounded"></div>
							<div class="name">'.$started['suite'].'</div>
							<div class="stats"></div>
							<div class="expand button">'.$suite_expand.'</div>
						</div>
						<div class="more '.$suite_display.'">
							<hr class = "big" />
						';
				
					$right = '</div></div>';
				
					$out[] = $left.implode('', $test).$right;
				}	
				$started = $event;
				$test = array();
				$suite_success = 'success';
			} elseif($event['event'] == 'test') {
				$status = '';
				switch ($event['status']) {
					case 'pass':
						$status = 'success';
						break;
					case 'error' && (stristr($event['message'], 'skipped') !== false):
						$status = 'skipped';
						break;
					case 'error' && (stristr($event['message'], 'incomplete') !== false):
						$status = 'incomplete';
						break;
					case 'fail' || 'error':
						$status = 'failure';
						break;
				}
				
				if($status == 'incomplete' && $suite_success != 'failure' && $suite_success != 'skipped') {
					$suite_success = 'incomplete';
				} if($status == 'skipped' && $suite_success != 'failure') {
					$suite_success = 'skipped';
				} elseif($status == 'failure') {
					$suite_success = 'failure';
				}
				
				$temp = explode('::', $event['test']);
				$name = end($temp);
				$message = (isset($event['message'])) ? $event['message'] : '';
				$expand = ($status == 'fail') ? '-' : '+';
				$display = ($status == 'fail') ? 'show' : 'hide';
				$trace = (isset($event['trace'])) ? $event['trace'] : '';
				$show_trace = ($trace) ? 'show' : 'hide';
				
				if($message) {
					$message_pieces = explode('Failed', $message);
					$first = array_shift($message_pieces);
					$message_rest = implode('Failed', $message_pieces);
					$message_rest = str_replace($first, '', $message_rest);
					$first = '<strong>'.$first.'</strong><br />';
					if($message_rest) {
						$message = $first.'Failed'.$message_rest;
					} else {
						$message = $first;
					}
				}
				
				if($trace) {
					$newTrace = array();
					foreach ($trace as $j => $arr) {
						if((stristr($arr['file'], 'phpunit.php') === false) &&
							(stristr($arr['file'], 'Main/index.php') === false) &&
							((!isset($arr['class'])) || (stristr($arr['class'], 'phpunit') === false))
						) {
							$newTrace[] = $arr;
						}
					}
					
					if(!empty($newTrace)) {
						ob_start();
						print_R($newTrace);
						$trace = (string) ob_get_contents();
						ob_end_clean();
					} else {
						$trace = '';
						$show_trace = 'hide';
					}
					
				}
				
				$variables = (isset($event['collected'])) ? $event['collected'] : '';
				$show_variables = ($variables) ? 'show' : 'hide';
				
				$test[] = '<div class="test '.$status.'">
					<div class="light rounded"></div>
					<div class="name">'.$name.'</div>
					<div class="stats">'.$message.'</div>';
									
				if($variables && $status == 'failure') {
					$test[] = '<div class="expand button">-</div>';
					$display = 'show';
				}
				elseif ($variables || $trace) {
					$test[] = '<div class="expand button">'.$expand.'</div>';
				}	
				
				$test[] = '<div class="more test '.$display.'">
						<div class="variables rounded '.$show_variables.'">
							<pre>'.trim($variables).'</pre>
						</div>
						<div class="stacktrace rounded '.$show_trace.'">
							<pre>'.trim((string)$trace).'</pre>
						</div>
					</div>
				</div>
				';
				
				if(isset($results[$i+1]) && $results[$i+1]['event'] != 'suiteStart') {
					$test[] = '<hr class = "small" />';
				}
			}	
				
		}
		
		if(isset($started)) {
			// Close out last case
			$suite_expand = ($suite_success == 'failure') ? '-' : '+';
			$suite_display = ($suite_success == 'failure') ? 'show' : 'hide';
			
			$left = '<div class="box rounded">
				<div class="testsuite '.$suite_success.'">
					<div class="light rounded"></div>
					<div class="name">'.$started['suite'].'</div>
					<div class="stats"></div>
					<div class="expand button">'.$suite_expand.'</div>
				</div>
				<div class="more '.$suite_display.'">
					<hr class = "big" />
				';
			
			$right = '</div></div>';
			
			$out[] = $left.implode('', $test).$right;
			
		}
		
		// Deal with last test case
		
		$out = implode('', $out);
		
		// Fill in unknowns
		
		
		// exit(0);
		
		return $out;
	}
	
	private static function push($old, $new, $subject) {
		// echo $old;echo " | ";echo htmlspecialchars($new);echo "<br/>";
		$pos = strpos($subject,$old);
		
		if ($pos !== false)
		    return substr_replace($subject,$new,$pos,strlen($old));
		else
			throw new Exception("Cannot find tag to replace (old: $old, new: ".htmlspecialchars($new).")", 1);
	}
	
	private static function pull($str, $start = '{', $end = '}') {
		$tags = array();
		$nest = -1;
		$start_mark = 0;
		
		for ($i=0; $i < strlen($str); $i++) { 
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
// 
// $phpunit = new PHPUnit('../ScarletFinal/tests');
// echo $phpunit;

?>