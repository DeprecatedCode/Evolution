<?php

namespace Evolution\Utility;
use \Exception;

/**
 * JSON Utility Class
 * Adds proper exception throwing and loading JSON from file.
 * @author Nate Ferrero
 */
class JSON {
    public static function encode($obj) {
        return json_encode($obj);
    }
    
    public static function decodeFile($file, $toAssoc = false) {
        if(!is_file($file))
            throw new Exception("File Not Found: $file");
        try {
            return self::decode(file_get_contents($file), $toAssoc);
        } catch(Exception $exception) {
            throw new Exception($exception->getMessage() . " in file $file", 0, $exception);
        }
    }
    
    public static function decode($json, $toAssoc = false) {
        $result = json_decode($json, $toAssoc);
        $error = self::error();
        
        if(!is_null($error))
            throw new Exception('JSON Error: '.$error);        
        
        return $result;
    }
    
    public static function error() {
        switch(json_last_error()) {
            case JSON_ERROR_NONE:
                return null;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return 'Unknown error';
        }
    }
}