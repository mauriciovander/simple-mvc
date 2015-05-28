<?php

// place your personal config settings in application scope
// SUGGESTION: use /usr/share/php5/global_config.php and set 
// your valid paths in php.ini
include 'global_config.php';

define('SYSTEM',__DIR__);
define('BASE_CLASSES',realpath(SYSTEM.'/base-classes'));
define('APPLICATION',realpath(SYSTEM.'/../application'));
define('MODELS',realpath(APPLICATION.'/models'));
define('CONTROLLERS',realpath(APPLICATION.'/controllers'));
define('VIEWS',realpath(APPLICATION.'/views'));
define('CLASSES',realpath(APPLICATION.'/classes'));
define('INCLUDES',realpath(APPLICATION.'/includes'));
define('CONFIG',realpath(APPLICATION.'/config'));
define('LOGS',realpath(APPLICATION.'/logs'));

require CONFIG.'/constants.php';

require BASE_CLASSES.'/base-controller.php';
require BASE_CLASSES.'/base-model.php';
require BASE_CLASSES.'/base-class.php';

require SYSTEM.'/autoloader.php';
require SYSTEM.'/db.php';
require SYSTEM.'/router.php';


// Start
$router = new Router;

try {
	$router->execute();
}
catch(Controller_Exception $e) { 
	echo $e->getMessage();
}

