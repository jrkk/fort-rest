<?php
namespace App\Driver\Logger;

use App\Config\LoggerConfig;
use App\Config\AppConfig;
use App\Driver\Logger\Log;
use App\Files\FileObject;
use App\Files\FileModes;

use App\Exception\FileNotWritableException;

class FileLogger extends BaseLogger implements LoggerConfig {

    use \App\Helper\Path;
    use \App\Helper\Temporial;

    protected $file;

    private $name = '';
    protected $delimiter = "\n";

    public function handler() {
        $filepath = $this->getFilePath();
        $this->file = new FileObject($filepath, FileModes::CW);
        if($this->file instanceof FileObject ) {  
            chmod($filepath, self::FILE_PERMISSION);  
            return true; 
        }
    }
    public function push($name = '') {  
        $this->name = $name;
        if($this->handler()) {
            if(!$this->file->isWritable())
                throw new FileNotWritableException('File not opened with write permissions');
            $totalBytes = 0;
            foreach( $this->messages as $index => $message ){
                $content = '';
                $content = "{$index}\t{$message}{$this->delimiter}";
                $totalBytes += $this->file->fwrite($content, strlen($content));
            }
            $this->messages = []; 
        }
    }
    private function getFilePath() {
        $stamp = $this->getDateStamp(self::FILE_STAMP,'-');
        $filename = self::FILE_PREFIX."{$this->name}-{$stamp}".self::FILE_EXT;
        $filepath = self::FILE_PATH.FILE_DELIMITER.$filename;
        $this->santize_file_path($filepath);
        return $filepath;
    }

    protected function interpolate(&$message, array $context = []) {
        if(self::EXEC_VARS === true) {

        }
        $message .= var_export($context, true);
    }

}