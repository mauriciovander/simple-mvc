<?

interface Base_Class_Interface {}

abstract class Base_Class implements Base_Class_Interface {
  // error_log('Loading class '.get_called_class ());
	public function __construct () {}
	public function __destruct () {}
}
