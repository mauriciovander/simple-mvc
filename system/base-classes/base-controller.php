<?

interface Base_Controller_Interface { public function index(); }

class Controller_Exception extends Exception {
	public function __construct($message, $error_code = 0, Exception $previous = null) {
		parent::__construct ( $message, $error_code, $previous );
		$this->log = new Monolog\Logger(get_called_class());
		$this->log->pushHandler(new Monolog\Handler\StreamHandler(LOGS.'/app.log', Monolog\Logger::ERROR));
		$this->log->addError($message);
	}		
}

abstract class Data_Object implements JsonSerializable{
	public $value;
	public $method;
	public $type;
	public function __construct($value) { $this->value = $value; }
	public function jsonSerialize() { return $this->value; }
	public function __sleep() { return array('value','method','type'); }
	public function __toString() { return $this->value; }
}

class Input_Data_Object extends Data_Object { public $type = 'input'; }
class Output_Data_Object extends Data_Object { public $type = 'output'; }

abstract class Base_Controller implements Base_Controller_Interface {

	private $input;
	private $output;
	private $router;
	protected $log;

	public function __toString() { return json_encode($this->output); }
	public function __set($k,$v) { $this->input->{$k} = new Input_Data_Object($v); }
	public function __get($k) {
		if(isset($this->input->{$k})) return $this->input->{$k};
		else throw new Controller_Exception(get_called_class ()."->$k is not defined", 1);
	}

	public function __destruct () {}
	public function __construct (Router $router) {
	  $this->router = $router;
		$this->input = new StdClass();
		$this->output = new StdClass();
		$this->log = new Monolog\Logger(get_called_class());
		$this->log->pushHandler(new Monolog\Handler\StreamHandler(LOGS.'/app.log', Monolog\Logger::INFO));
	}

  public function render(Array $data = array(), $template_path = null) {
    foreach($data as $k => $v) $$k = $v;
    if(is_null($template_path)) $template_path = $this->router->controller.'/'.$this->router->action.'.php';
    try {
      include(VIEWS.'/'.$template_path);
    }
    catch(Exception $e) {
      throw new Controller_Exception('Missing template at '.$template_path, 0, $e);
    }
  }
  
	public function bypass() { $this->output = clone $this->input; }
}
