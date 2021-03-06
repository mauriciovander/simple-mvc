<?php

interface Base_Controller_Interface {

}

class Controller_Exception extends Exception {

    public function __construct($message, $error_code = 0, Exception $previous = null) {
        parent::__construct($message, $error_code, $previous);
        $this->log = new Monolog\Logger(get_called_class());
        $this->log->pushHandler(new Monolog\Handler\StreamHandler(LOGS . '/app.log', Monolog\Logger::ERROR));
        $this->log->addError($message);
    }

}

class Controller_Success extends Controller_Exception { }

class Input_Data_Object extends Data_Object {

    public $type = 'input';

}

class Output_Data_Object extends Data_Object {

    public $type = 'output';

}

abstract class Base_Controller implements Base_Controller_Interface {

    protected $input;
    protected $output;
    protected $router;
    protected $log;

    public function __toString() {
        return json_encode($this->output);
    }

    public function __set($k, $v) {
        $this->input->{$k} = new Input_Data_Object($v);
    }

    public function __get($k) {
        if (isset($this->input->{$k})) {
            return $this->input->{$k};
        } else {
            throw new Controller_Exception(get_called_class() . "->$k is not defined", 1);
        }
    }

    public function __destruct() {
        
    }
    
    public function __construct(Router $router) {
        $this->router = $router;
        $this->input = new StdClass();
        $this->output = new StdClass();
        $this->log = new Monolog\Logger(get_called_class());
        $this->log->pushHandler(new Monolog\Handler\StreamHandler(LOGS . '/app.log', Monolog\Logger::INFO));
    }

    private function setHeader($message, $code) {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept');
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache');
        header('Expires: 0');
        header("HTTP/1.0 " . $code . " " . $message);
    }

    protected function error($message = 'Error', $code = 400, $data = null) {
        $this->setHeader($message, $code);
        $response = new stdClass();
        $response->message = $message;
        $response->status = 'error';
        $response->code = $code;
        $response->data = (!is_null($data)) ? $data : $this->output;
        echo json_encode($response);
    }

    protected function success($message = 'Success', $code = 200, $data = null) {
        $this->setHeader($message, $code);
        $response = new stdClass();
        $response->message = $message;
        $response->status = 'success';
        $response->code = $code;
        $response->data = (!is_null($data)) ? $data : $this->output;
        echo json_encode($response);
    }
    
    public function render(Array $data = array(), $template_path = null) {
        foreach ($data as $k => $v) {
            $$k = $v;
        }
        if (is_null($template_path)) {
            $template_path = $this->router->controller . '/' . $this->router->action . '.php';
        }
        try {
            include(VIEWS . '/' . $template_path);
        } catch (Exception $e) {
            throw new Controller_Exception('Missing template at ' . $template_path, 0, $e);
        }
    }

    public function bypass() {
        $this->output = clone $this->input;
    }

}
