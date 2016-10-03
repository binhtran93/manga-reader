<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Services\Crawler;

use Exception;

/**
 * Description of ChapterException
 *
 * @author Tran
 */
class ChapterException extends Exception {
    
    private $logFile = 'chapter-log.txt';

    public function __construct($message, $code = null, $previous = null) {
        parent::__construct($message);
        
        
    }
    
}
