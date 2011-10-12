<?php

namespace Evolution\Extend;
use \Exception;
use Evolution\Kernel;


class DefaultBundle {
	
	public function __system_initialize($bundle) {
		// Include any assets
		foreach(glob(Kernel::$bundlePaths[$bundle].'/assets/*') as $file)
		    require_once($file);
	}
}