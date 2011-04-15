<?php
//
// jQuery File Tree PHP Connector
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// Output a list of files for jQuery File Tree
//

$final = '';

$dir = realpath(urldecode($_GET['dir']));

if ( $dir ) {
    $dir .= '/';
    $files = scandir($dir);
    if ( count($files) > 2 ) { /* The 2 accounts for . and .. */
        $final .= "<ul class='jqueryFileTree' style='display: none;'>";
        natcasesort($files);
        // All dirs
        foreach ( $files as $file ) {
            if( file_exists($dir . $file) && $file != '.' && $file != '..' && is_dir($dir . $file) ) {
                $final .= "<li class='directory collapsed'><a href='#' rel='" . htmlentities($dir . $file) . "/'>" . htmlentities($file) . "</a></li>";
            }
        }
        // All files
        foreach ( $files as $file ) {
            if ( file_exists($dir . $file) && $file != '.' && $file != '..' && !is_dir($dir . $file) ) {
                $ext = preg_replace('/^.*\./', '', $file);
                $final .= "<li class='file ext_$ext'><a href='#' rel='" . htmlentities($dir . $file) . "'>" . htmlentities($file) . "</a></li>";
            }
        }
        $final .= "</ul>";	
    }
}

echo $final;

?>
