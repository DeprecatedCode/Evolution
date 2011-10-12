<?php

namespace Evolution\Bundles\Prototypes;
use \Evolution\Kernel;
use \Exception;

class Default {
	
	public function __system_initialize($bundle) {
        
		// Include any assets
        foreach(Kernel::$bundlePaths[$bundle] as $dir) {
    		foreach(glob($dir.'/assets/*') as $file) {
    		    require_once($file);
    		}
        }
	}
}