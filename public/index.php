<?

session_start();

$loader = require '../vendor/autoload.php';

require '../system/bootloader.php';

$app = new Application();


try {
	$app->execute();
}
catch(Controller_Exception $e) { 
	echo $e->getMessage();
}
