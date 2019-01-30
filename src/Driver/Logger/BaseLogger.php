<?php
namespace App\Driver\Logger;

use App\Config\LoggerConfig;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\AbstractLogger; 

abstract class BaseLogger extends AbstractLogger implements LoggerInterface, LoggerConfig {

    abstract protected function interpolate(&$message, array $context = []);
    abstract public function push();

    public function log($level, $message, array $context = []) : self  {

        if (! \in_array($level, self::ALLOWED_LEVELS)) {
			throw new \Exception('undefined logger mode has used');
        }

        if( \in_array(\get_class($this) , [ FileLogger::class, NullLogger::class ])
            && count($context) > 0 ) {
            $this->interpolate($message, $context);
        }

        $this->messages[\microtime()] =  "{$level}\t{$message}";
        return $this;

    }

}