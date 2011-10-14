<?php

namespace Evolution\Startup;
use \Exception;

/**
 * Evolution Startup Script
 * @author Nate Ferrero
 */
    
// Include System Configuration
require_once(__DIR__ . '/configure.php');

// This does the rest!
\Evolution\Configure::run();