<?

interface Base_Controller_Interface {
	public function index();
}

class Controller_Exception extends Exception {
	public function __construct($message, $error_code = 0, Exception $previous = null) {
		parent::__construct ( $message, $error_code, $previous );
		$this->log = new Monolog\Logger(get_called_class());
		$this->log->pushHandler(new Monolog\Handler\StreamHandler(LOGS.'/app.log', Monolog\Logger::ERROR));
		$this->log->addError($message);
	}		
}

abstract class Data_Object {
	public $value;

	public function __construct($value,$method = null){
		$this->value = $value;
	}

	public function __toString(){
		return $this->value;
	}
}

class Input_Data_Object extends Data_Object {
	public $method;
	public $type = 'input';
}

class Output_Data_Object extends Data_Object {
	public $method;
	public $type = 'output';
}


abstract class Base_Controller implements Base_Controller_Interface {

	private $input;
	private $output;
	protected $log;

	public function __set($k,$v,$method = null){
		$this->input->{$k} = new Input_Data_Object($v, $method);
	}

	public function __get($k){
		if(isset($this->input->{$k})) return $this->input->{$k};
		else throw new Controller_Exception(get_called_class ()."->$k is not defined", 1);
	}

	public function __toString(){
		return json_encode($this->output);
	}

	public function __construct () {
		$this->input = new StdClass();
		$this->output = new StdClass();

		$this->log = new Monolog\Logger(get_called_class());
		$this->log->pushHandler(new Monolog\Handler\StreamHandler(LOGS.'/app.log', Monolog\Logger::INFO));
//		$this->log->addInfo('Enter controller');

	}

	public function __destruct () {
//		$this->log->addInfo('Exit Controller');
//		$this->log->addInfo('input', (array)$this->input);
//		$this->log->addInfo('output',(array)$this->output);
	}

	public function bypass() {
		$this->output = clone $this->input;
	}
}
