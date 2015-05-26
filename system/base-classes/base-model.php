<?

interface Base_Model_Interface {}

abstract class Base_Model implements Base_Model_Interface {
  // error_log('Loading model '.get_called_class ());
	public function __construct () {}
	public function __destruct () {}
	public function __toString(){ return json_encode($this); }
	public function load() {}
	public function save() {}
	public function update() {}

}


