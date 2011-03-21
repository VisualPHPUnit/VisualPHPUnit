<?php

    require 'config.php';
    require 'VPU.php';

    $path = realpath(TEST_DIRECTORY); 	
    if ( !is_dir($path) ) 
    {
        // TODO: Throw exception
        die("Could not find the specified tests directory: <strong>" . $path . '</strong>');
    } 

    chdir($path);
    ob_start(); 

    $vpu = new VPU($path);

    if ( SANDBOX_ERRORS )
    {
        set_error_handler(array($vpu, 'handle_errors'));
    }

    $results = $vpu->run();

    include 'ui/header.html';
    echo $vpu->to_HTML($results);
    include 'ui/footer.html';

    if ( CREATE_SNAPSHOTS )
    {
        $snapshot = ob_get_contents(); 
        $vpu->create_snapshot($snapshot, 'html');
    }

?>
