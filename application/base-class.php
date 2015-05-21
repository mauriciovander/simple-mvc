<?

interface Base_Class_Interface {

}

abstract class Base_Class implements Base_Class_Interface {

	public function __construct () {
		error_log('Loading class '.get_called_class ());
	}

	public function __destruct () {
		error_log('Exit class '.get_called_class ());
	}
}
