<?php

class DB_Exception extends Exception {
	public function __construct($message, $error_code = 0, Exception $previous = null) {
		parent::__construct ( $message, $error_code, $previous );
		$this->log = new Monolog\Logger(get_called_class());
		$this->log->pushHandler(new Monolog\Handler\StreamHandler(LOGS.'/app.log', Monolog\Logger::ERROR));
		$this->log->addError($message);
	}		
}

// Singleton
class DB {
  private static $instance = null;
  protected $dbh;
  
  private function __construct() { 
    try{
      $connection_string = 'mysql:host=' . MYSQL_HOST . ';port=' . MYSQL_PORT . ';dbname=' . MYSQL_DATABASE;
      $this->dbh = new PDO($connection_string, MYSQL_USER, MYSQL_PASSWORD, array( PDO::ATTR_PERSISTENT => false));  
    }
    catch (PDOException $e) {
      throw new Controller_Exception('Connection Error', 1, $e);
    }
  }
  
  static function getInstance() {
    if(is_null(self::$instance)) self::$instance = new DB;
    return self::$instance;
  }  
  
  public function __destruct(){
     $this->dbh = null;
  }
  
  public function execute($sql,Array $params = array()) {
    try{
      $stmt = $this->dbh->prepare($sql);
      foreach($params as $n=>$v) {
        $stmt->bindValue($n+1, $v);
      }
      if(!$stmt->execute()){
        unset($stmt);
        throw new Controller_Exception($stmt->errorInfo(), 1, $e);
      }
      $rows = $stmt->fetchAll();
      unset($stmt);
      return $rows;
    }
    catch (PDOException $e) {
      throw new Controller_Exception($e->getMessage(), 1, $e);
    }
  }
  
}
