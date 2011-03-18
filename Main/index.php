<?php

$action = isset($_GET['action']) ? $_GET['action'] : 'main';	
function_exists($action) ? $action() : error();

exit(0);

function main() {
    
    include('header.php');
    
    include '../PHPUnit.php';
    require_once '../Sandbox.php';

    // Protect against unauthorized file access. 
    $path = str_replace('../', '', $_GET['path']); 
    $_GET['path'] = realpath(dirname(__FILE__) . '/../../' . $path); 	
    if (isset($_GET['path']) && is_dir($_GET['path'])) 
    {
        $suite = (isset($_GET['test'])) ? $_GET['test'] : '';
        
        $phpunit = new PHPUnit($_GET['path'], $suite);
        
        chdir($_GET['path']);
    } 
    else 
    {
        throw new Exception("Could not find the specified tests directory: <strong>".$_GET['path'].'</strong>', 1);
    }
            
    $results = $phpunit->run();
    // print_r($results);
    echo $phpunit->toHTML($results);
    
    include('footer.php');
    
    // include('main.php');
}

?>
