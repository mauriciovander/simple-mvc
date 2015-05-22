<?

interface Base_Controller_Interface {

}

abstract class Base_Controller implements Base_Controller_Interface {

	private $input,$output;

	public function setOutput($k, $v) {
		$this->output->{$k} = $v;
	}

	public function setInput($k, $v) {
		$this->input->{$k} = $v;
	}

	public function __set($k,$v){
		$this->setInput($k,$v);
	}

	public function __get($k){
		if(isset($this->input->{$k})) return $this->input->{$k};
		else throw new Exception(get_called_class ()."->$k is not defined", 1);
	}

	public function __construct () {
		error_log('Loading controller '.get_called_class ());
		$this->input = new StdClass();
		$this->output = new StdClass();
	}

	public function __destruct () {
		error_log('Exit controller '.get_called_class ());
		echo json_encode($this->output);
	}

	public function echoInput() {
		$this->output = clone $this->input;
	}
}
