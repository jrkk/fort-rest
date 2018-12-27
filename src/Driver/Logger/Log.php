<?php
namespace App\Driver\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\AbstractLogger; 

class Log extends AbstractLogger implements LoggerInterface {

    const LEVELS = [
		'emergency'	 => 1,
		'alert'		 => 2,
		'critical'	 => 3,
		'error'		 => 4,
		'warning'	 => 5,
		'notice'	 => 6,
		'info'	 	 => 7,
		'debug'		 => 8,
	];

    use \App\Config\LoggerConfig;
    use \App\Helper\PropertiesBinder;

    protected $messages = [];
    function __construct()
    {
        $this->bindConfiguration();
    }
    public function log($level, $message, array $context = []) 
    {
        $stamp = microtime(true);
        $stamp .= "\t".(new \DateTime())->format('u');
        if ( !array_key_exists($level, self::LEVELS)) {
			throw new \Exception('undefined logger mode has used');
        }
        if ( !in_array($level, $this->allowedModes)) {
			return $this;
        }
        if(in_array(get_class(), [
            \App\Driver\Logger\FileLogger::class,
            \App\Driver\Logger\MLogger::class])) {
            $this->interpolate($message, $context);
        }
        $this->messages[] = $stamp."\t".$level."\t".$message;
        return $this;
    }
    private function interpolate(&$message , $context = []) 
    {
        var_export($message, $context);
    }
    public function push() {
        echo "\n".implode("\n", $this->messages);
        $this->messages = [];
    }
}