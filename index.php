<?php

if(dirname(__FILE__) == realpath(getcwd()) && !isset($_GET['path'])) {
	throw new Exception("Add path to your test files in the URL like: ?path=/YourBigProject/tests/)", 1);
}

if(isset($_GET['path'])) {
	$path = $_GET['path'];
} else {
	$path = getcwd();
}

header('Location: /PHPUnit-Test-Report/Main/index.php?path='.$path);


?>