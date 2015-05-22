<?

define('SYSTEM',__DIR__);
define('CONFIG',realpath(SYSTEM.'/config'));
define('CLASSES',realpath(SYSTEM.'/classes'));

define('APPLICATION',realpath(SYSTEM.'../application'));
define('MODELS',realpath(APPLICATION.'/models'));
define('CONTROLLERS',realpath(APPLICATION.'/controllers'));
define('VIEWS',realpath(APPLICATION.'/views'));
define('INCLUDES',realpath(APPLICATION.'/includes'));

require CONFIG.'/constants.php';

require 'autoloader.php';

