<?php

    if ( !isset($_GET['path']) ) 
    {
        die("Add path to your test files in the URL like: ?path=/YourBigProject/tests/)");
    }

    header('Location: Main/index.php?path=' . $_GET['path']);

?>
