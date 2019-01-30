<?php
namespace App\Driver\Logger;

use App\Config\LoggerConfig;

class NullLogger extends BaseLogger implements LoggerConfig {
    
    protected function interpolate(&$message, array $context = []) {
        if(self::DEBUG === false)  return $this;
        if(self::EXEC_VARS === true) {

        }
        $message .= var_export($context, true);
        var_dump($message);
    }

    public function push() {
        if(self::DEBUG === false)  return $this;
        echo "\n ---------------------- \n";
        foreach ($this->messages as $key => $message) {
            echo $key."\t".$message."\n";
        }
        $this->messages = [];
    }

}
