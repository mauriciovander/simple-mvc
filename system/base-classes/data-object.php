<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Data_Object implements JsonSerializable {

    public $value;
    public $method;
    public $type;

    public function __construct($value) {
        $this->value = $value;
    }

    public function jsonSerialize() {
        return $this->value;
    }

    public function __sleep() {
        return array('value', 'method', 'type');
    }

    public function __toString() {
        return $this->value;
    }

}
