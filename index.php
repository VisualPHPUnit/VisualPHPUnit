<?php

    require 'config.php';
    require 'PHPUnit.php';
    require 'Sandbox.php';

    include 'ui/header.html';

    $path = realpath(TEST_DIRECTORY); 	
    if ( !is_dir($path) ) 
    {
        // TODO: Throw exception
        die("Could not find the specified tests directory: <strong>" . $path . '</strong>');
    } 
            
    chdir($path);

    $phpunit = new PHPUnit($path);
    $results = $phpunit->run();
    echo $phpunit->to_HTML($results);
    
    include 'ui/footer.html';

?>
