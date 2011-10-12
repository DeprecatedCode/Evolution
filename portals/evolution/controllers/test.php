<?php

namespace Controllers\Test;
use Evolution\Kernel as e;

use \Exception;

/**
 * Evolution Test Controller
 * @author Nate Ferrero
 */
class Controller {
	
	public function apple() {
		e::members();
		echo 'test';
		return 1;
	}

}