<?php
namespace App\Driver\Logger;

use App\Config\LoggerConfig;
use App\Config\AppConfig;
use App\Driver\Logger\Log;
use App\Files\FileObject;
use App\Files\FileModes;

use App\Exception\FileNotWritableException;

class FileLogger extends Log {

    use \App\Helper\Path;
    use \App\Helper\Temporial;

    protected $file;

    protected $ext = '.log';
    protected $path = BASEPATH.FILE_DELIMITER.'storage'.FILE_DELIMITER.'logs';
    protected $filePrefix = 'log-';
    protected $permission = 0755;
    protected $stamp = 'Y-m-d-h-i';

    private $name = '';
    protected $delimiter = "\n";

    function __construct() {
       parent::__construct();
    }
    public function handler() {
        $filepath = $this->getFilePath();
        $this->file = new FileObject($filepath, FileModes::CW);
        if($this->file instanceof FileObject ) {  
            chmod($filepath, $this->permission);  
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
                $content = "{$message}{$this->delimiter}";
                $totalBytes += $this->file->fwrite($content, strlen($content));
            }
            //echo $totalBytes." Bytes of information written on file.";
            $this->messages = []; 
        }
    }
    private function getFilePath() {
        $stamp = $this->getDateStamp($this->stamp,'-');
        // generate the file path
        $filename = "{$this->filePrefix}{$this->name}-{$stamp}{$this->ext}";
        $filepath = $this->path.FILE_DELIMITER.$filename;
        $this->santize_file_path($filepath);
        return $filepath;
    }
}