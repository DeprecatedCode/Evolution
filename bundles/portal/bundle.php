<?php

    // Add the standard portal path if none set
    if(count($this->portalPaths) == 0)
        $this->portalPaths[] = dirname(dirname(__DIR__)) . '/portals';
    
    // Paths where this portal exists
    $paths = array();
    
    // Portal Name
    $name = strtolower($route[0]);
    
    // Check for portal in paths
    foreach($this->portalPaths as $path) {
        $path .= '/' + $name;
        if(is_dir($path))
            $paths[] = $path;
    }
    
    // If any paths matched
    if(count($paths) > 0) {
        
    }