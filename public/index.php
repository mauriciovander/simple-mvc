<?

session_start();

$loader = require '../vendor/autoload.php';

require '../system/bootloader.php';

$router = new Router();


try {
	$router->execute();
}
catch(Controller_Exception $e) { 
	echo $e->getMessage();
}
