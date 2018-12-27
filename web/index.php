<?php

error_reporting(E_ALL);
ini_set('display_errors','On');

define('FCPATH',__DIR__);
define('BASEPATH',dirname(__DIR__));
define('FILE_DELIMITER','\\');


require_once BASEPATH."/vendor/autoload.php";

\App\Core\System::Init();
\App\Core\System::Start();
\App\Core\System::Stop();