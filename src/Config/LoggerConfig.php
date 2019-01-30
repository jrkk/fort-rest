<?php
namespace App\Config;

interface LoggerConfig {

    const DEBUG = true;
    const EXEC_VARS = false;

    const STAMP = 'u';

    const FILE_EXT = '.log';
    const FILE_PATH = BASEPATH.FILE_DELIMITER.'storage'.FILE_DELIMITER.'logs'.FILE_DELIMITER;
    const FILE_PREFIX = '';
    const FILE_PERMISSION = 0777;
    const FILE_STAMP = 'Y-m-d-H-i/3';

    const ALLOWED_LEVELS = ['debug','info','emergency','alert','critical','error','warning','notice'];
}