<?php // Standard Controller

$action = isset($_GET['action']) ? $_GET['action'] : 'main';	
function_exists($action) ? $action() : error();

exit(0);

function main() {
	// include_once($_SERVER['DOCUMENT_ROOT'].'/ScarletFinal/classes/Template.php');
	
	// $template = new Template('main.tpl/');
	
	include('header.php');
	
	// $template->compile('main.tpl');
	require_once '../Sandbox.php';
	include '../PHPUnit.php';
		
		
	if(isset($_GET['path']) && is_dir($_GET['path'])) {
		$suite = (isset($_GET['test'])) ? $_GET['test'] : '';
		
		$phpunit = new PHPUnit($_GET['path'], $suite);
		
		chdir($_GET['path']);
	} else {
		throw new Exception("Could not find the specified tests directory: <strong>".$_GET['path'].'</strong>', 1);
	}
		
	$results = $phpunit->run();
	// print_r($results);
	echo $phpunit->toHTML($results);
	
	include('footer.php');
	
	
	// include('main.php');
}

?>