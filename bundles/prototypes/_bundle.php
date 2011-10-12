<?php

namespace Evolution\Bundles\Prototypes;
use \Evolution\Kernel;
use \Exception;

/**
 * Evolution Bundle Prototypes
 * Suitable for extending by other bundles
 * @author Nate Ferrero
 */
class Bundle {
    
    // Load a class to extend
    public function load($name) {
        require_once(__DIR__ . '/classes/' . strtolower($name) . '.php');
    }
    
}