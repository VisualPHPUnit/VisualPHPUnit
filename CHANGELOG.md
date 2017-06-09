# Change Log
All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/).

This project adheres to [Keep a CHANGELOG](http://keepachangelog.com/)

## [Unreleased]

## [3.1.0] - 2016-11-14

### Added

- #172 Let the user select the backend service in the UI
- #174 Include phpDox documentation on website

### Changed

- #171 Move matching pattern for test cases to config file
- #173 Upgrade backend to support Silex 2.x

## [3.0.0] - 2016-05-16

Complete rewrite with more or less the same functionality.

### Changed

- Database support is now default enabled (sqlite)
- Tests stats are now logged automatically for graphing.
- Snapshots are now archived in the database and not in the filesystem.
- VPU now utilize the PHPUnit API directly instead of parsing the JSON output from the command-line.
- VPU now consist of tree separate apps: Frontend (WEB), Backend (REST) and Console.

### Removed

- Parsing of phpunit.xml is not supported in this initial release of 3.x but will be added in future releases.
- You cannot migrate your existing log/database data.

## [2.3.2] - 2016-05-16

### Fixed
- #153 Load xml bootstrap file

## [2.3.1] - 2015-11-14

### Fixed
- Downgraded phpunit to known working version 	
- #143 Replace the call to PHPUnit_TextUI_Command->run by shell_exec because it's buggy on some versions
- #146 runWithXml()

## [2.3.0] - 2015-07-15

Final release of version 2. All new development will be for version 3.

### Added

- #112 Composer support
- #111 Travis support
- #110 Scrutinizer support
- #109 Switch to new maintainer
- Added primary directory grouping to test file list

### Changed

- #122 Updated phpdoc
- #113 Add PSR2 compliance
- #100 Snapshot always return success message
- #98 Add message to failed test for string comparisons that shows expected and actual Strings
- Override default phpunit execution
- Larger view on wide screen
- The test_directories array in bootstrap.php gained a key for each directory. This key is used to group files and sub-directories with the given primary test directory. This keeps projects together and makes it easier to select an entire project.
- The Javascript for FileSelector, as well as the FileList ajax handler were also updated. The Home controller needed a minor tweak since it expected numeric keys in the test_directories array
- Composer instead of PEAR + VPU.php error fixes
- Changed pear_path over to composer_vendor_path and adjusted include paths + require file name
- Also adjusted VPU.php to prevent the throwing of headers already sent errors
- Use protocol relative urls when loading external resources
- Change views to use protocol relative urls for loading of external resources
- Updated to latest stable version of phpunit

## [2.2.0] - 2013-05-13

### Added

- #93 Add CLI switches to allow config overrides
- #93 Add support for multiple XML files
- #93 Allow for multiple test directories to be specified

### Changed

- #96 Throw exception if cache permissions are incorrect

## [2.1.1] - 2013-02-11

### Added

- #67 Implement keyboard shortcuts

### Changed

- #87 Pad snapshot time with zero for better sorting
- #90 Don't allow duplicate files if the parent folder is selected
- #84 Only collect JSON when using XML configuration files
- #83 Show server error if AJAX request fails
- #80 Don't rewrite PHP_SELF
- #78 Use namespace when checking if tests are subclasses of PHPUnit_Framework_TestCase
- #60 Clarify directory selection key combination
- #59 Check if tests are subclasses of PHPUnit_Framework_TestCase

### Fixed

- #53 Fix WAMP routing issues
- #65 Fix output parsing to handle pretty-printed JSON
- #63 Fix display of statistics

## [2.1.0] - 2012-08-20

### Added

- #57 Add ability to ignore hidden files
- #48, #58 Add error handler for non-JSON responses from the server
- #54 Handle unbalanced braces properly

### Changed

- #56 Use strict checking with readdir()
- #45 Reduce complexity of Apache installations
- #44 Only return child directories of test_directory

### Fixed

- #50 Fix error that occurs when no snapshot is selected on Archives page
- #46 Fix autoloader to only load files required by VPU

## [2.0.0] - 2012-02-17

### Added

- #31 Add ability to run tests using a phpunit.xml configuration file
- #32 Add ability to generate test results from the command line

### Changed
- Overhaul the entire code base
- Give the UI a facelift

## [1.5.6] - 2012-02-17

### Added

- #37 Add ability to set MySQL port

### Changed

- #42 Replace line breaks with <br>s instead of empty strings

### Fixed

- #41 Fix jqueryFileTree folder selection for Macs
- #39 Fix display of debugging output

## [1.5.5] - 2012-02-17

### Changed

- #34 Change require -> require_once to avoid errors
- #33 Don't require files to share the same name as the test classes

### Fixed

- #23 Fix output buffering

## [1.5.4] - 2011-11-15

### Fixed

- #26 Fix include_path issues

## [1.5.3] - 2011-11-09

### Changed

- #20 Update history file
### Fixed

- #21 Fix SANDBOX_IGNORE settings

## [1.5.2] - 2011-08-22

### Added

- #17 Add tooltips to compensate for colorblind usage problem
- #14 Add ability to filter suite results

## [1.5.1] - 2011-07-15

### Added

- #9 Allow debug display of JSON within tests

### Changed

- Update color scheme
- #10 Update snapshot list each time a test is run

### Fixed

- #11 Fix snapshot filenames to be compatible with Windows systems
- Fix POST locations to use relative URIs

## [1.5.0] - 2011-05-31

### Added

- Add ability to generate graphs of test results

## [1.4.1] - 2011-05-25

### Added
- Add a progress bar to indicate that tests are being processed

### Fixed

- Fix Windows path issues

## [1.4.0] - 2011-05-23

### Changed

- Overhaul the UI
- Implement a better check for archived files

### Fixed

- Fix issues with namespaced tests

## [1.3.2] - 2011-05-01

### Added

- Add support for bootstraps
- Add the ability to view snapshots from the homepage

### Changed
- Clean up the user interface
- Change the snapshot filename format to Y-m-d

## [1.3.1] - 2011-04-19

### Changed

- Allow for relative paths in TEST_DIRECTORY
- Use a better test loading mechanism

## [1.3.0] - 2011-04-18

### Added

- Add a start page to allow for specific choosing of tests and options
- Add the ability to sort suite results by status and time

### Changed

- Clean up some configuration settings

### Removed

- Remove ability to save JSON snapshots

## [1.2.0] - 2011-04-09

### Added

- Add statistic bars to display the suite results visually

## [1.1.1] - 2011-04-07

### Fixed

- Fix to allow for loading a single test directly
- Adjust code to allow for proper execution with 'short_open_tag' off
- Fix to match test files with the word 'Test' at the end of the filename
- Fix to eliminate duplicate tests

## [1.1.0] - 2011-04-07

### Added

- Add suite statistics

## [1.0.0] - 2011-04-07

### Added

- Initial release
