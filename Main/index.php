<?php

    include 'header.php';
    
    include '../PHPUnit.php';
    require '../Sandbox.php';

    $path = str_replace('../', '', $_GET['path']); 
    $path = realpath(dirname(__FILE__) . '/../../' . $path); 	
    if ( is_dir($path) ) 
    {
        $phpunit = new PHPUnit($path);
        chdir($path);
    } 
    else 
    {
        die("Could not find the specified tests directory: <strong>" . $_GET['path'] . '</strong>');
    }
            
    $results = $phpunit->run();
    echo $phpunit->toHTML($results);
    
    include 'footer.php';

?>