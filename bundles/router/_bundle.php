<?php

namespace Evolution\Bundles\Router;
use \Evolution\Kernel;
use \Exception;
use \Evolution\Bundles\Bindings\Completion;

/**
 * Evolution Router Bundle
 * @author Nate Ferrero
 */
class Bundle {
    
    public $server;
    public $client;
    
    /**
     * Invoke bundle, i.e. e::router()
     * Handle the request
     * @author Nate Ferrero
     */
    public function route() {
        
        // Save server variables
        $this->server = (object) array(
            'data'      => $_POST,
            'query'     => $_GET,
            'name'      => $_SERVER['SERVER_NAME'],
            'address'   => $_SERVER['SERVER_ADDR'],
            'port'      => $_SERVER['SERVER_PORT'],
            'host'      => $_SERVER['HTTP_HOST'],
            'path'      => $_SERVER['REDIRECT_URL'],
            'root'      => $_SERVER['DOCUMENT_ROOT'],
            'protocol'  => $_SERVER['SERVER_PROTOCOL']
        );
        
        // Save client information
        $this->client = (object) array(
            'address'   => $_SERVER['REMOTE_ADDR'],
            'port'      => $_SERVER['REMOTE_PORT'],
            'userAgent' => $_SERVER['HTTP_USER_AGENT']
        );
        
        // Check for path
        if(!isset($this->server->path))
            throw new Exception('Routing path not set');
        
        // Get path array and clean
        $path = explode('/', $this->server->path);
        if($path[0] === '')
            array_shift($path);
        if($path[count($path) - 1] === '')
            array_pop($path);
            

        // Get clean path string
        $pstr = '/' . implode('/', $path);
        
        // Execute all bound methods
        try {
            // This is what actually does any routing
            Kernel::bindings('router:route')->execute($path);
            
            // If no match was made
            throw new NotFoundException("Resource not found");
        }
        
        // Handle successful routing
        catch(Completion $object) {
            
            // Do nothing once the request is routed
            // TODO perhaps consider logging?
        }
        
        // Handle any exceptions
        catch(Exception $exception) {
            
            // Update exception
            $exception = new Exception($exception->getMessage() . " for path `$pstr`", 0, $exception);
            
            // Try to resolve with error pages
            Kernel::bindings('router:exception')->execute($path, $exception);
            
            // Else throw the error
            throw $exception;
        }
    }
}

/**
 * Not Found Exception
 * For general routing use
 */
class NotFoundException extends Exception {}