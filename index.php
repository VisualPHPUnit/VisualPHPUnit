<?php

    require 'config.php';
    require 'VPU.php';
    //require 'Sandbox.php';

    $path = realpath(TEST_DIRECTORY); 	
    if ( !is_dir($path) ) 
    {
        // TODO: Throw exception
        die("Could not find the specified tests directory: <strong>" . $path . '</strong>');
    } 
            
    chdir($path);
    ob_start(); 

    $vpu = new VPU($path);
    $results = $vpu->run();

    include 'ui/header.html';
    echo $vpu->to_HTML($results);
    include 'ui/footer.html';

    // USE SET_ERROR_HANDLER

    if ( CREATE_SNAPSHOTS )
    {
        $snapshot = ob_get_contents(); 
        $vpu->create_snapshot($snapshot, 'html');
    }

?>
