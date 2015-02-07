<?php
define('ROOT_PATH', dirname(dirname(__FILE__)));

define('APPLICATION_PATH', ROOT_PATH . '/application');

$application = new Yaf_Application(ROOT_PATH . '/conf/application.ini');

$application->bootstrap()->run();
?>
