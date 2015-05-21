<?

require '../application/bootloader.php';

echo OS;

echo '<pre>';

new Test_Model();

$c = new Test_Controller();

$c->i1 = 'test 1';
$c->i2 = 'test 2';

$c->echoInput();

// new Test_Controller();


echo '</pre>';
