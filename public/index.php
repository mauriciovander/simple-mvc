<?

session_start();

$loader = require '../vendor/autoload.php';

require '../system/bootloader.php';

$app = new Application();
$app->execute();
