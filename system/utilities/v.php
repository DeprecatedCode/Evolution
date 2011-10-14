<?php

/**
 * Simple and useful debugging methods
 * @author Nate Ferrero
 */
 
// For passing objects by reference
function v(&$obj, $die = true) {
	require_once(__DIR__.'/dBug/dBug.php');
	new dBug($obj);
	if($die)
		die;
}

// For passing anything, including objects, not-by-reference
function vv($obj, $die = true) {
	require_once(__DIR__.'/dBug/dBug.php');
	$x = new dBug($obj);
	echo '<hr/>';
	$x->getVariableName();
	if($die)
		die;
}