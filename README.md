[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VisualPHPUnit/VisualPHPUnit/badges/quality-score.png?b=devel)](https://scrutinizer-ci.com/g/VisualPHPUnit/VisualPHPUnit/?branch=devel)
[![Build Status](https://travis-ci.org/VisualPHPUnit/VisualPHPUnit.svg)](https://travis-ci.org/VisualPHPUnit/VisualPHPUnit)
[![Dependency Status](https://www.versioneye.com/user/projects/55f547b3a4155f00090005b5/badge.svg?style=flat)](https://www.versioneye.com/user/projects/55f547b3a4155f00090005b5)
[![Project Stats](https://www.openhub.net/p/VisualPHPUnit/widgets/project_thin_badge.gif)](https://www.openhub.net/p/VisualPHPUnit)


# VisualPHPUnit

VisualPHPUnit is a visual front-end for PHPUnit.
You may read more about it here [here](http://visualphpunit.github.io/VisualPHPUnit/) or in the [wiki](https://github.com/VisualPHPUnit/VisualPHPUnit/wiki).

## Installation

1. Download and extract (or git clone) the project to a web-accessible directory.
2. Change the permissions of `app/resource/cache` to `777` or give the Apache user write access another way.
3. If you have not already installed PHPUnit via Composer, do so using the following command:
	1. cd /path/to/VisualPHPUnit/../
	2. composer require phpunit/phpunit
3. Open `app/config/bootstrap.php` with your favorite editor.
    1. Within the `$config` array, change `composer_vendor_path` so that it points to the Composer vendor directory where PHPUnit is located (if installed with the above step, this shouldn't need to be edited)
    2. Within the `$config` array, change the contents of `test_directories` to reflect the location(s) of your unit tests. Note that each directory acts as a root directory.
4. Configure your web server (see below).

## Project Configuration (optional)

VPU comes with many of its features disabled by default.  In order to take advantage of them, you'll have to modify a few more lines in `app/config/bootstrap.php`.

### <a name='graph-generation'></a>Graph Generation

If you'd like to enable graph generation, you will have to do the following:

1. Within the `$config` array, change `store_statistics` to `true`.  If you'd like, you can keep this set as `false`, though you will have to change the 'Store Statistics' option to 'Yes' on the UI if you want the test statistics to be used in graph generation.
2. Run the migration `app/resource/migration/01_CreateSchema.sql` against a MySQL database.
    - Note that this will automatically create a database named `vpu` with the tables needed to save your test statistics.
3. Within the `$config` array, change the settings within the `db` array to reflect your database settings.
    - Note that if you're using the migration described above, `database` should remain set to `vpu`.
    - The `plugin` directive should not be changed.

### <a name='snapshots'></a>Snapshots

If you'd like to enable snapshots, you will have to do the following:

1. Within the `$config` array, change `create_snapshots` to `true`.  If you'd like, you can keep this set as `false`, though you will have to change the 'Create Snapshots' option to 'Yes' on the UI if you want the test results to be saved.
2. Within the `$config` array, change `snapshot_directory` to a directory where you would like the snapshots to be saved.
    - Note that this directory must have the appropriate permissions in order to allow PHP to write to it.
    - Note that the dropdown list on the 'Archives' page will only display the files found within `snapshot_directory`.

### <a name='sandboxing'></a>Error Sandboxing

If you'd like to enable error sandboxing, you will have to do the following:

1. Within the `$config` array, change `sandbox_errors` to `true`.  If you'd like, you can keep this set as `false`, though you will have to change the 'Sandbox Errors' option to 'Yes' on the UI if you want the errors encountered during the test run to be sandboxed.
2. Within the `$config` array, change `error_reporting` to reflect which errors you'd like to have sandboxed.  See PHP's manual entry on [error_reporting](http://php.net/manual/en/function.error-reporting.php) for more information.

### Ignore Hidden Folders

By default, the file selector does not display hidden folders (i.e., folders with a '.' prefix).  If you'd like to display hidden folders, you will have to do the following:

1.  Within the `$config` array, change `ignore_hidden_folders` to `false`.

### <a name='xml-configuration'></a>PHPUnit XML Configuration File

If you'd like to use a [PHPUnit XML configuration file](http://www.phpunit.de/manual/current/en/appendixes.configuration.html) to define which tests to run, you will have to do the following:

1. Within the `$config` array, change `xml_configuration_files` to reflect the location(s) where the configuration file(s) can be found.
2. Modify your PHPUnit XML configuration file(s) to include this block:

```xml
       <!-- This is required for VPU to work correctly -->
       <listeners>
         <listener class="PHPUnit_Util_Log_JSON"></listener>
       </listeners>
```

### Bootstraps

If you'd like to load any bootstraps, you will have to do the following:

1. Within the `$config` array, list the paths to each of the bootstraps within the `bootstraps` array.

## Keyboard Shortcuts

### Home Page

`t - Run Tests`

## Running VPU at the Command Line

VPU can be run at the command line, making it possible to automate the generation of test results via cron.

You may append --help for options

### Usage

```bash
# from the project root
bin/vpu
```

