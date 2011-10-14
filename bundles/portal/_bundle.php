<?php

namespace Evolution\Bundles\Portal;
use \Evolution\Kernel;
use \Evolution\Configure;
use \Exception;
use \Evolution\Bundles\Bindings\Completion;
use \Evolution\Bundles\Router\NotFoundException;

/**
 * Standard configuration
 */
Configure::add('portal.location', Kernel::$root . '/portals');

/**
 * Evolution Portal Bundle
 * @author Nate Ferrero
 */
class Bundle {

    // Route the portal
    public function route($path) {
        
        // Check for null first segment
        if(!isset($path[0]))
            return false;
        
        // Paths where this portal exists
        $dirs = array();
        
        // Portal Name
        $name = strtolower($path[0]);
        
        // Get portal paths
        $searchdirs = Configure::getArray('portal.location');
        
        // Check for portal in paths
        foreach($searchdirs as $dir) {
            $dir .= '/' . $name;
            if(is_dir($dir))
                $dirs[] = $dir;
        }
        
        // If any paths matched
        if(count($dirs) > 0) {
            
            // Remove the first segment
            array_shift($path);
            
            // Process the portal bindings
            try {
                $matches = Kernel::bindings('portal:route')->execute($path, $dirs);

                // If no match was made
                $pstr = '/' . implode('/', $path);
                throw new NotFoundException("Resource not found at `$pstr`");
            }
            
            // Handle successful routing
            catch(Completion $object) {
                throw $object;
            }
            
            // Handle any exceptions
            catch(Exception $exception) {
                
                // Update exception
                $exception = new Exception($exception->getMessage() . " in portal `$name`", 0, $exception);
                
                // Try to resolve with error pages
                Kernel::bindings('portal:exception')->execute($dirs, $path, $exception);
                
                // Else throw the error
                throw $exception;
            }
        }
    }
}