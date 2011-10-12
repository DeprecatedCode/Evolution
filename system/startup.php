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


// Include some basic extensions
foreach(glob(__DIR__ . '/extend/*') as $file)
    require_once($file);

// Include Evolution
require_once(__DIR__ . '/kernel.php');

// Kernel access
use \Evolution\Kernel;

// Handle errors
set_error_handler(Kernel::$errorHandler);

// Handle exceptions
set_exception_handler(Kernel::$exceptionHandler);

// Route the request
Kernel::router()->route();