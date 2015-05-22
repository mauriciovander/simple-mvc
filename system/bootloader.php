<?

define('APPLICATION',__DIR__);
define('CONFIG',realpath(APPLICATION.'/config'));
define('INCLUDES',realpath(APPLICATION.'/includes'));
define('MODELS',realpath(APPLICATION.'/models'));
define('VIEWS',realpath(APPLICATION.'/views'));
define('CLASSES',realpath(APPLICATION.'/classes'));
define('CONTROLLERS',realpath(APPLICATION.'/controllers'));

require CONFIG.'/constants.php';

require 'autoloader.php';

