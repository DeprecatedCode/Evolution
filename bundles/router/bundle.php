<?php

namespace Evolution\Bundles\Router;
use Evolution\Kernel;
use \Exception;

/**
 * Evolution Router Bundle
 * @author Nate Ferrero
 */
class Bundle {
    
    public $server;
    public $client;
    
    /**
     * Invoke bundle, i.e. e::router($server, $client)
     * Handle the request
     * @author Nate Ferrero
     */
    public function __invoke_bundle($server, $client) {
        
        // Save variables
        $this->server = (object) $server;
        $this->client = (object) $client;
        
        // Check for path
        if(!isset($this->server->path))
            throw new Exception('Routing path not set');
        
        // Get path array and clean
        $path = explode('/', $this->server->path);
        if($path[0] === '')
            array_shift($path);
        if($path[count($path) - 1] === '')
            array_pop($path);
            
        // Execute all bound methods
        // This is what actually does any routing
        Kernel::bindings('router:route')->execute($path);
        
        // Nothing matched
        throw new Exception("No route found for /" . implode('/', $path));
    }
}