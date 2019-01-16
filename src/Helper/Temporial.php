<?php
namespace App\Helper;

trait Temporial {

    protected function getDateStamp($stamp, $delimiter = "\s") { // primary preference.
        $dt = new \DateTimeImmutable('now');
        if(preg_match("/^([a-zA-z]+(".$delimiter.")?)+$/", $stamp)) {
            return $dt->format($stamp);
        } 
        if($delimiter === "\s") $delimiter = ' ';
        $formaters = explode($delimiter, $stamp);
        $expression = "";
        $frags = [];
        foreach($formaters as $format) {
            $val = null;
            $val = eval('return '.$dt->format("".$format." ").' ;');
            if(is_float($val)) {
                $val = intVal($val);
            }
            if($val !== null && $val !== false) {
                $frags[] = $val;
            }
        }
        if(count($frags) > 0) {
            return implode($delimiter, $frags);
        }
        return '';

    }

}