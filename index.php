<?php

    if ( !isset($_GET['path']) ) 
    {
        // TODO: Throw exception
        die("Add path to your test files in the URL like: ?path=/YourBigProject/tests/)");
    }

    require 'config.php';
    require 'PHPUnit.php';
    require 'Sandbox.php';

    include 'header.html';

    $path = str_replace('../', '', $_GET['path']); 
    $path = realpath(dirname(__FILE__) . '/' . $path); 	
    if ( !is_dir($path) ) 
    {
        // TODO: Throw exception
        die("Could not find the specified tests directory: <strong>" . $_GET['path'] . '</strong>');
    } 
            
    chdir($path);

    $phpunit = new PHPUnit($path);
    $results = $phpunit->run();
    echo $phpunit->to_HTML($results);
    
    include 'footer.html';

?>
