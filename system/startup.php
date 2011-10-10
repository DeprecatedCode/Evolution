<?php

namespace Evolution\Startup;
use \Exception;

/**
 * Evolution Startup Script
 * @author Nate Ferrero
 */
 
// Change dir to keep evolution clean
chdir(dirname(__DIR__) . '/tmp');

// Include some basic utilities
foreach(glob(__DIR__ . '/utilities/*') as $file)
    require_once($file);

// Include Evolution
require_once(__DIR__ . '/evolution.php');

// Short access to the kernel
use Evolution\Kernel as e;

// Handle errors
set_error_handler(e::$errorHandler);

// Handle exceptions
set_exception_handler(e::$exceptionHandler);

// Route the request
e::router(

    array(
        'data'      => $_POST,
        'query'     => $_GET,
        'name'      => $_SERVER['SERVER_NAME'],
        'address'   => $_SERVER['SERVER_ADDR'],
        'port'      => $_SERVER['SERVER_PORT'],
        'host'      => $_SERVER['HTTP_HOST'],
        'path'      => $_SERVER['REDIRECT_URL'],
        'root'      => $_SERVER['DOCUMENT_ROOT'],
        'protocol'  => $_SERVER['SERVER_PROTOCOL']
    ),
    
    array(
        'address'   => $_SERVER['REMOTE_ADDR'],
        'port'      => $_SERVER['REMOTE_PORT'],
        'userAgent' => $_SERVER['HTTP_USER_AGENT']
    )

);