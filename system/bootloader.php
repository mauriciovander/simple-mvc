<?

define('SYSTEM',__DIR__);
define('CONFIG',realpath(SYSTEM.'/config'));
define('INCLUDES',realpath(SYSTEM.'/includes'));
define('MODELS',realpath(SYSTEM.'/models'));
define('VIEWS',realpath(SYSTEM.'/views'));
define('CLASSES',realpath(SYSTEM.'/classes'));
define('CONTROLLERS',realpath(SYSTEM.'/controllers'));

require CONFIG.'/constants.php';

require 'autoloader.php';

