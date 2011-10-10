<?php

namespace Evolution\Bundles\Bindings;
use Evolution\Utility;
use Evolution\Kernel;
use \Exception;

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
    public function __invoke_bundle($bundle) {
        
        // Check if bindings need to be loaded
        if(is_null(self::$bindings))
            self::loadBindings();
            
        // Return a binding collection
        return new Collection(
            isset(self::$bindings[$bundle]) ? self::$bindings[$bundle] : array()
        );
    }
    
    private static function loadBindings() {
        foreach(Kernel::$bundlePaths as $bundle => $paths) {
            foreach($paths as $path) {
                
                // Check for a bindings json file
                $path .= '/bindings.json';
                
                if(is_file($path)) {
                    self::$bindings[$bundle] = Utility\JSON::decodeFile($path);
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
    
    public function __construct($items) {
        $this->items = $items;
    }
    
    public function execute() {
        var_dump(func_get_args());
    }
}