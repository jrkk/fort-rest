<?php
namespace App\Config;

trait LoggerConfig {
    protected $config = [
        'ext' => '.log',
        'path' => BASEPATH.FILE_DELIMITER.'storage'.FILE_DELIMITER.'logs'.FILE_DELIMITER,
        'filePrefix' => '',
        'permission' => 0777,
        'stamp' => 'Y-m-d-H-i/3',
    ];
    protected $allowedModes = ['debug','info','emergency','alert','critical','error','warning','notice'];
}