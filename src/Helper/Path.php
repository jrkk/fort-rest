<?php
namespace App\Helper;

trait Path {
    public function santize_file_path(&$filepath, $sperator = '-') {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $filepath = str_replace("/","\\", $filepath);
        }
        $filepath = str_ireplace(' ', $sperator, $filepath);
    }
}