<?

define('SYSTEM',__DIR__);
define('CONFIG',realpath(SYSTEM.'/config'));
define('BASE_CLASSES',realpath(SYSTEM.'/base-classes'));

define('APPLICATION',realpath(SYSTEM.'/../application'));
define('MODELS',realpath(APPLICATION.'/models'));
define('CONTROLLERS',realpath(APPLICATION.'/controllers'));
define('VIEWS',realpath(APPLICATION.'/views'));
define('CLASSES',realpath(APPLICATION.'/classes'));
define('INCLUDES',realpath(APPLICATION.'/includes'));

require CONFIG.'/constants.php';

require 'autoloader.php';

