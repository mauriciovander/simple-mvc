<?

define('SYSTEM',__DIR__);
define('CONFIG',realpath(SYSTEM.'/config'));
define('BASE_CLASSES',realpath(SYSTEM.'/base-classes'));
define('APPLICATION',realpath(SYSTEM.'/../application'));
define('MODELS',realpath(APPLICATION.'/models'));
define('CONTROLLERS',realpath(APPLICATION.'/controllers'));
define('VIEWS',realpath(APPLICATION.'/views'));
define('CLASSES',realpath(APPLICATION.'/classes'));
define('INCLUDES',realpath(APPLICATION.'/includes'));
define('LOGS',realpath(APPLICATION.'/logs'));

require CONFIG.'/constants.php';

require 'autoloader.php';

class Router {
	private $controller;
	private $action;

	public function __construct() {

		$parts = explode ( '/', $_REQUEST['rt'] );
		unset($_REQUEST['rt']);
		
		$o = array('controller','action','params');
		
		$this->controller = reset($parts);
		if(empty($this->controller)) $this->controller = 'index';
		$controller_name = ucwords(strtolower($this->controller)).'_Controller';
		$this->controller = new $controller_name($this);

		$this->action = next($parts);
		if(empty($this->action)) $this->action = 'index';
		$this->action = strtolower($this->action);

		$k = next($parts);
		while($k){
			$v = next($parts);
			if(!empty($v)) {
				$this->controller->{$k}= $v;
				$this->controller->{$k}->method = 'get';
			}
			$k = next($parts);
		}
		foreach($_GET as $k=>$v){
			$this->controller->{$k}= $v;
			$this->controller->{$k}->method = 'get';
		}
		foreach($_POST as $k=>$v){
			$this->controller->{$k}= $v;
			$this->controller->{$k}->method = 'post';
		}
	}

	public function execute() {
		$this->controller->{$this->action}();
	}
}
