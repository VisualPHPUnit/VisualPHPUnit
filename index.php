<?php

    if ( dirname(__FILE__) == realpath(getcwd()) && !isset($_GET['path']) ) 
    {
        die("Add path to your test files in the URL like: ?path=/YourBigProject/tests/)");
    }

    $path = ( isset($_GET['path']) ) ? $_GET['path'] : getcwd();

    header('Location: Main/index.php?path='.$path);

?>
