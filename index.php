<?php

    require 'config.php';
    require 'VPU.php';
    require 'Sandbox.php';

    $path = realpath(TEST_DIRECTORY); 	
    if ( !is_dir($path) ) 
    {
        // TODO: Throw exception
        die("Could not find the specified tests directory: <strong>" . $path . '</strong>');
    } 
            
    chdir($path);

    $phpunit = new VPU($path);
    $results = $phpunit->run();

    ob_start(); 
    include 'ui/header.html';
    echo $phpunit->to_HTML($results);
    include 'ui/footer.html';
    $snapshot = ob_get_contents(); 
    ob_end_clean();
    
    echo $snapshot;

    if ( CREATE_SNAPSHOTS )
    {
        $phpunit->create_snapshot($snapshot, 'html');
    }

?>
