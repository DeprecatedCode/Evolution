<?php

namespace Evolution\Bundles\Bindings;
use \Evolution\Kernel;
use \Exception;
use \Evolution\Utility\JSON;

/**
 * Evolution Bindings Bundle
 * @author Nate Ferrero
 */
class Bundle {
    
    private static $bindings = null;
    
    /**
     * Invoke bundle, i.e. e::bindings('bundle:method')
     * Create a collection of bindings
     * @author Nate Ferrero
     */
    public function __invoke_bundle($name) {
        
        // Check if bindings need to be loaded
        if(is_null(self::$bindings))
            self::loadBindings();
        
        // Return a binding collection
        return new Collection(
            isset(self::$bindings[$name]) ? self::$bindings[$name] : array()
        );
    }
    
    // Return and stop execution
    public function complete($value = null) {
        throw new Completion($value);
    }
    
    private static function loadBindings() {
        foreach(Kernel::$bundlePaths as $bundle => $paths) {
            foreach($paths as $path) {
                
                // Check for a bindings json file
                $path .= '/bindings.json';
                
                if(is_file($path)) {
                    
                    // Load and verify items
                    $items = JSON::decodeFile($path);
                    if(!is_array($items))
                        throw new Exception("Bindings list is not JSON array in file `$path`");
                        
                    // Add items to bindings array
                    $index = 0;
                    foreach($items as $item) {
                        
                        // Keep track of binding number
                        $index++;
                        
                        // Check for incorrect binding format
                        if(!isset($item->name) || !isset($item->method)) {
                            $json = JSON::encode($item);
                            throw new Exception("JSON bindings entry #$index: `$json`, is malformed in file `$path`");   
                        }
                        
                        // Check if entry exists
                        if(!isset(self::$bindings[$item->name]))
                            self::$bindings[$item->name] = array();
                            
                        // Finally add the binding
                        self::$bindings[$item->name][] = (object) array(
                            'bundle' => $bundle,
                            'method' => $item->method
                        );
                    }
                }
            }
        }
    }
}

/**
 * Evolution Bindings Collection
 * @author Nate Ferrero
 */
class Collection {
    
    private $items;
    
    // Store the items for use
    public function __construct($items) {
        $this->items = $items;
    }
    
    // Execute the list of items
    public function execute() {
        
        // Prepare return array
        $return = array();
        
        // Loop through collection
        foreach($this->items as $item) {
            
            // Call each item and add to return value
            $bundle = $item->bundle;
            $return[] = call_user_func_array(
                array(Kernel::$bundle(), $item->method),
                func_get_args()
            );
        }

        // Return the array
        return $return;
    }
}

/**
 * Bindings Completion Exception
 */
class Completion extends Exception {
    public $value;
    
    public function __construct($value) {
           $this->value = $value;
    }
}