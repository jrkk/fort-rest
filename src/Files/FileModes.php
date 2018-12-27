<?php

namespace App\Files;

class FileModes {
    const RO = 'r';
    const RW = 'r+';
    const WO = 'w';
    const WR = 'w+';
    const CW = 'a';
    const CRW = 'a+';
    const CO = 'x';
    const CRWO = 'x+';
}