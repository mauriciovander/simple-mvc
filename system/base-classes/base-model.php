<?php

interface Base_Model_Interface {}

abstract class Base_Model implements Base_Model_Interface {
	private $db;
  	// error_log('Loading model '.get_called_class ());
	public function __construct () { $this->db = DB::getInstance(); }
	public function __destruct () {}
	public function __toString(){ return json_encode($this); }
	public function load() {}
	public function save() {}
	public function update() {}

}


