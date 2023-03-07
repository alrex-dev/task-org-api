<?php
spl_autoload_register(function ($class_name) {
	$preg_match = preg_match('/^Controllers\\\/', $class_name);

	if (1 === $preg_match) {
		$class_name = preg_replace('/\\\/', '/', $class_name);
		$class_name = preg_replace('/^Controllers\\//', '', $class_name);
        
		require_once(__DIR__ . '/controllers/' . $class_name . '.php');
	}
});

spl_autoload_register(function ($class_name) {
	$preg_match = preg_match('/^Library\\\/', $class_name);

	if (1 === $preg_match) {
		$class_name = preg_replace('/\\\/', '/', $class_name);
		$class_name = preg_replace('/^Library\\//', '', $class_name);
        
		require_once(__DIR__ . '/library/' . $class_name . '.php');
	}
});
?>