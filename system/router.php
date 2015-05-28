<?php

class Router {
	private $controller_instance;

  public $controller;
  public $action;
  
	public function __construct() {

		$parts = explode ( '/', $_REQUEST['rt'] );
		unset($_REQUEST['rt']);
		
		$o = array('controller','action','params');
		
		$this->controller = reset($parts);
		if(empty($this->controller)) $this->controller = 'index';
		$controller_name = ucwords(strtolower($this->controller)).'_Controller';
		$this->controller_instance = new $controller_name($this);

		$this->action = next($parts);
		if(empty($this->action)) $this->action = 'index';
		$this->action = strtolower($this->action);

		$k = next($parts);
		while($k){
			$v = next($parts);
			if(!empty($v)) {
				$this->controller_instance->{$k}= $v;
				$this->controller_instance->{$k}->method = 'get';
			}
			$k = next($parts);
		}
		foreach($_GET as $k=>$v){
			$this->controller_instance->{$k}= $v;
			$this->controller_instance->{$k}->method = 'get';
		}
		foreach($_POST as $k=>$v){
			$this->controller_instance->{$k}= $v;
			$this->controller_instance->{$k}->method = 'post';
		}
	}

	public function execute() {
		$this->controller_instance->{$this->action}();
	}
}
