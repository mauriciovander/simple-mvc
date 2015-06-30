<?php

class DB_Exception extends Exception {

    public function __construct($message, $error_code = 0, Exception $previous = null) {
        parent::__construct($message, $error_code, $previous);
        $this->log = new Monolog\Logger(get_called_class());
        $this->log->pushHandler(new Monolog\Handler\StreamHandler(LOGS . '/app.log', Monolog\Logger::ERROR));
        $this->log->addError($message);
    }

}

// Singleton
class DB {

    private static $instance = null;
    private $dbh;
    private $lastId;

    private function __construct($db_config){
        try {
            $connection_string = 'mysql:host=' . $db_config::HOST . ';port=' . $db_config::PORT. ';dbname=' . $db_config::NAME;
            $this->dbh = new PDO($connection_string, $db_config::USER, $db_config::PASSWORD);
        } catch (PDOException $e) {
            throw new Controller_Exception('Connection Error', 1, $e);
        }
    }

    static function getInstance($db_config) {
        if (is_null(self::$instance)) {
            self::$instance = new DB($db_config);
        }
        return self::$instance;
    }

    public function __destruct() {
        $this->dbh = null;
    }

    public function lastId() {        
        return $this->lastId;
    }

    public function begin() {
        $this->dbh->beginTransaction();
    }

    public function commit() {
        $this->dbh->commit();
    }

    public function rollBack() {
        $this->dbh->rollBack();
    }

    public function execute($sql, Array $params = array()) {
//        die( $this->debug($sql, $params));
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute($params);
            $this->lastId = $this->dbh->lastInsertId();            
            $result = $sth->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } catch (PDOException $e) {
            throw new DB_Exception('SQL ERROR: ' . $this->debug($sql, $params), 1, $e);
        }
        return false;
    }

    private function debug($statement, array $params = []) {
        $statement = preg_replace_callback(
                '/[?]/', function ($k) use ($params) {
            static $i = 0;
            return sprintf("'%s'", $params[$i++]);
        }, $statement
        );

        echo '<pre>Query Debug:<br>', $statement, '</pre>';
    }

}
