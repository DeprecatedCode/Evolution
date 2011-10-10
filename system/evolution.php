<?php

namespace Evolution;
use \Exception;

/**
 * Evolution Kernel
 * The kernel does only two things, load bundles and handle fatal exceptions
 * @author Nate Ferrero
 */
class Kernel {
    
    private static $bundles = array();
    
    public static $bundlePaths = null;
    
    public static $errorHandler;
    
    public static $exceptionHandler;
    
    public static function __callStatic($name, $arguments) {
        
        // Bundles are never created twice
        $name = strtolower($name);
        if(!isset(self::$bundles[$name]))
            self::bundle($name);
        
        // Calling a bundle with arguments
        if(count($arguments) > 0) {
            return call_user_func_array(
                array(self::$bundles[$name], '__invoke_bundle'),
                $arguments
            );
        }
        
        // Otherwise, return the bundle
        return self::$bundles[$name];
    }
    
    // Load bundle paths
    private static function loadBundlePaths() {
        
        // Reset bundle paths
        self::$bundlePaths = array();
        
        // Look for bundles in these directories
        $searchLocations = array(
            dirname(__DIR__) . '/bundles'
        );
        
        // Check each location for bundles
        foreach($searchLocations as $location) {
            foreach(glob($location . '/*', GLOB_ONLYDIR) as $dir) {
                $name = basename($dir);
                
                // Add bundle to collection
                if(!isset(self::$bundlePaths[$name]))
                    self::$bundlePaths[$name] = array();
                    
                // Add path to collection
                self::$bundlePaths[$name][] = $dir;
            }
        }
    }
    
    // Load a bundle
    private static function bundle($name) {
        
        // Check if bundle paths have been loaded
        if(is_null(self::$bundlePaths))
            self::loadBundlePaths();
        
        // Check for bundle
        if(isset(self::$bundlePaths[$name])) {
            foreach(self::$bundlePaths[$name] as $path) {
                
                // Check for bundle file
                $path .= '/bundle.php';
                
                if(is_file($path)) {
                    
                    // Include the file
                    require_once($path);
                    
                    // Check for bundle class
                    $class = "Evolution\\Bundles\\$name\\Bundle";
                    
                    if(class_exists($class)) {
                        
                        // Load bundle
                        self::$bundles[$name] = new $class;
                        return true;
                    }
                }
            }
        }
        
        // Not found
        throw new Exception("Bundle `$name` not found");
    }
}

// Default Error Handler
Kernel::$errorHandler = function($errno , $errstr, $errfile, $errline) {
    throw new Exception("$errstr <i>on line $errline of $errfile");
};

// Default Exception Handler
Kernel::$exceptionHandler = function($exception) {
    require(__DIR__ . '/exception.php');
};