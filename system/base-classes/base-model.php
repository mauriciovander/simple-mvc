<?

interface Base_Model_Interface {

}

abstract class Base_Model implements Base_Model_Interface {

	public function __construct () {
		error_log('Loading model '.get_called_class ());
	}

	public function __destruct () {
		error_log('Exit model '.get_called_class ());
	}

	public function __toString(){
		return json_encode($this);
	}
	
	public function save(){
		
	}
}


