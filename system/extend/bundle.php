<?php

namespace Evolution\Extend;
use \Exception;
use Evolution\Kernel;


class DefaultBundle {
	
	public function __system_initialize() {
		$bundle=strtolower(substr(substr(get_class($this),0,-7),strlen('Evolution\\Bundles\\')));
		// Include any assets
		foreach(glob(Kernel::$root . '/bundles/'.$bundle.'/assets/*') as $file)
		    require_once($file);
		// load configuration options
		// 
	}
}