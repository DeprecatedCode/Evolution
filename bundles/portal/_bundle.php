<?php

namespace Evolution\Bundles\Portal;
use Evolution\Kernel;
use \Exception;

/**
 * Evolution Portal Bundle
 * @author Nate Ferrero
 */
class Bundle {
    
    public $portalPaths = array();

    public function route($path) {
        
        // Check for null first segment
        if(!isset($path[0]))
            return false;
    
        // Add the standard portal path if none set
        if(count($this->portalPaths) == 0)
            $this->portalPaths[] = Kernel::$root . '/portals';
        
        // Paths where this portal exists
        $dirs = array();
        
        // Portal Name
        $name = strtolower($path[0]);
        
        // Check for portal in paths
        foreach($this->portalPaths as $dir) {
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
                $matches = Kernel::bindings('portal:route')->execute($dirs, $path);

                # @todo if no match was made 404 binding
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
            
            # @todo if no match was made 404 binding

        }
    }
}