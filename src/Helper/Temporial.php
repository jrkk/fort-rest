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
            $val = eval('return '.$dt->format($format).' ;');
            if(is_float($val)) {
                $val = intVal($val);
            }
            if($val !== null && $val !== false) {
                $frags[] = $val;
            }
            // if(preg_match('/^[a-zA-z]{1,}$/', $format)) {
            //     var_export('SUCCESS CASE'.$format);
            //     $frags[] = eval('return '.$dt->format($format).' ;');
            // } else {
            //     var_export('FAILURE CASE');
            //     $frags[] = eval('return '.$dt->format($format).' ;');
            //     // if(preg_match('/^([\S])+(\/|\+|\/|\%|\-){1}([\S])+$/', $format, $matches)) {
            //     //     var_export($matches);
            //     //     $leftOperand = $rightOperand = 0;
            //     //     if(is_numeric($matches[1])) {
            //     //         $leftOperand = $dt->format($matches[3]);
            //     //         $rightOperand = $matches[1];
            //     //     } else {
            //     //         $val = $dt->format($matches[1]);
            //     //         $rightOperand = $matches[3];
            //     //     }
            //     //     if(is_numeric($leftOperand) && is_numeric($rightOperand) ) {
            //     //         $leftOperand = intVal($leftOperand);
            //     //         $rightOperand = intVal($rightOperand);
            //     //         switch(trim($matches[2])) {
            //     //             case '+' : $frags[] = $leftOperand + $rightOperand ; echo "+"; break;
            //     //             case '-' : $frags[] = $leftOperand - $rightOperand ; break;
            //     //             case '/' : $frags[] = $leftOperand / $rightOperand ; break;
            //     //             case '*' : $frags[] = $leftOperand * $rightOperand ; break;
            //     //             case '%' : $frags[] = $leftOperand % $rightOperand ; break;
            //     //         }
            //     //     }
            //     // }
            // }
        }
        if(count($frags) > 0) {
            return implode($delimiter, $frags);
        }
        return '';

    }

}