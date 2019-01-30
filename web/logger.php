<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

define('FCPATH',__DIR__);
define('BASEPATH',dirname(__DIR__));
define('FILE_DELIMITER','/');

require_once BASEPATH."/vendor/autoload.php";

$logger = new \App\Driver\Logger\FileLogger();

$log = new stdClass();
$log->postVars = $_POST;
$log->getVars = $_GET;
$log->headerVars = $_SERVER;

$logger->log('debug','Process has init', (array)$log);
$logger->log('info','Process has init');
$logger->log('critical','Process has init')->push('process');

