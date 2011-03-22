VisualPHPUnit
=============

VisualPHPUnit is a visual front-end for PHPUnit.  Written in PHP, it aims to make unit testing more appealing. 

Features
--------

VisualPHPUnit provides the following features:

* A stunning front-end which organizes test and suite results
* An option to maintain a history of unit test results through the use of visual and JSON logs 
* Enumeration of PHPUnit statistics and messages
* Convenient display of any debug messages written within unit tests
* Sandboxing of PHP errors/exceptions

Screenshot
----------

.. image:: http://echodrop.net/code/vpu/ss.png

Installation
------------

1. Download and extract the project to a web-accessible directory.
2. Open config.php in your favorite editor.
   a) Change PHPUNIT_INSTALL so that it points to the directory where PHPUnit is installed.
   b) Update TEST_DIRECTORY so that it points to the directory which contains your unit tests.
   c) Update TEST_FILENAME if your test filenames do not contain the word "Test" (note that this is case-insensitive).
3. This should be enough for a basic installation.  However, if you'd like to enable additional features, you'll have to modify a few more lines in config.php. 
   a) Change CREATE_SNAPSHOTS to *true* if you'd like to enable logging.  Logs are stored in the "history" directory, though you can modify SNAPSHOT_DIRECTORY to point somewhere else if you please.  Please make note of the following:
      i. The directory specified in SNAPSHOT_DIRECTORY must contain two directories for logging to work properly.  One must be named 'html', and the other 'json'.
      ii. Note that you will have to give the directory specified in SNAPSHOT_DIRECTORY (as well as the subdirectories described above) the appropriate permissions in order to allow PHP to write to it.
      iii. Also note that you will have to copy the "ui" directory over to the directory specified in SNAPSHOT_DIRECTORY in order for the html files to display properly. 
   b) Change SANDBOX_ERRORS to *true* if you'd like VPU to display any PHP errors after the test results.  If so, please make note of the following:
      i. The file specified in SANDBOX_FILENAME will always be empty (VPU wipes it at the end of each test run).  However, PHP still needs to be able to write to it, so ensure that the filename specified with SANDBOX_FILENAME has the appropriate permissions. 

Feedback
--------

Feel free to send any feedback you may have regarding this project to NSinopoli@gmail.com. 

Credits
-------

Special thanks to Matt Mueller (http://mattmueller.me/blog/), who came up with the initial concept, wrote the original code (https://github.com/MatthewMueller/PHPUnit-Test-Report), and was kind enough to share it.
