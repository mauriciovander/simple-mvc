<?php

interface Base_Model_Interface {
    
}

abstract class Base_Model implements Base_Model_Interface {

    private $db;
    private $field_values;
    private $key;
    private $table;
    private $sql_verb;
    private $where_sql;
    private $join_sql;
    private $order_sql;
    private $group_sql;
    private $limit_sql;
    private $verb_sql;
    private $fields_sql;
    private $where_values;

// error_log('Loading model '.get_called_class ());
    public function __construct($db_config) {
        $this->db = DB::getInstance($db_config);
        $this->field_values = [];
        $this->where_sql = [];
        $this->where_values = [];
        $this->join_sql = [];
        $this->order_sql = [];
        $this->group_sql = [];
        return $this;
    }

    public function __destruct() {
        unset($this->field_values);
    }

    public function setTable($tname) {
        $this->table = $tname;
    }

    public function jsonSerialize() {
        return $this->field_values;
    }

    public function __toString() {
        return json_encode($this->field_values);
    }

    public function setKey($key_name) {
        $this->key = $key_name;
        return $this;
    }

    public function execute() {
        if (!isset($this->table)) {
            return false;
        }

        $sql = $this->sqlScript();
        if ($sql) {

            $q = array_values($this->field_values);
            foreach ($this->where_values as $v) {
                $q[] = $v;
            }

            $result = $this->db->execute($sql, array_values($q));

            switch ($this->verb_sql) {
                case 'SELECT':
                    if (count($result) == 1) {
                        $r = reset($result);
                        foreach ($r as $k => $v) {
                            $this->{$k} = $v;
                        }
                    } else {
                        return $result;
                    }
                    break;
                case 'UPDATE':
                    break;
                case 'INSERT':
                    $this->field_values[$this->key] = $this->db->lastId();
                    break;
                default:
                    return false;
            }
            return true;
        }
        return false;
    }

    public function select() {
        if (func_num_args() == 0) {
            $this->fields_sql = ['*'];
        } else {
            $this->fields_sql = array_values(func_get_args());
        }
        $this->verb_sql = 'SELECT';
        return $this;
    }

    public function insert() {
        $this->verb_sql = 'INSERT';
        return $this;
    }

    public function update() {
        $this->verb_sql = 'UPDATE';
        return $this;
    }

    private function sqlScript() {
        $sql = $this->verb_sql;
        switch ($this->verb_sql) {
            case 'SELECT':
                $sql .= ' ' . implode(', ', $this->fields_sql) . ' FROM ' . $this->table;
                break;
            case 'INSERT':
                $sql .= ' INTO ' . $this->table;
                $sql .= ' (' . implode(', ', array_keys($this->field_values)) . ')';
                $sql .= ' VALUES';
                $q = [];
                foreach ($this->field_values as $k) {
                    $q[] = '?';
                }
                $sql .= ' (' . implode(', ', $q) . ')';
                break;
            case 'UPDATE':
                $sql .= ' ' . $this->table . ' SET ';
                $q = [];
                foreach (array_keys($this->field_values) as $k) {
                    $q[] = "$k = ?";
                }
                $sql .= ' ' . implode(', ', $q);
                break;
            default:
                return false;
        }
        if (count($this->join_sql)) {
            $sql .= ' ' . implode(' ', $this->join_sql);
        }
        if (count($this->where_sql)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->where_sql);
        }
        if (count($this->group_sql)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->group_sql);
        }
        if (count($this->order_sql)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->order_sql);
        }
        if (count($this->limit_sql)) {
            $sql .= ' LIMIT ' . $this->limit_sql;
        }
        $sql .= ';';
        return $sql;
    }

    public function whereIn($field, Array $values = array()) {
        if (!isset($this->where_sql)) {
            $this->where_sql = [];
            $this->where_values = [];
        }
        $q = [];
        foreach ($values as $v) {
            $q[] = '?';
            $this->where_values[] = $v;
        }
        $qs = implode(',', $q);
        $this->where_sql[] = "$field in ($qs)";

        return $this;
    }

    public function where($field, $value, $operation = '=') {
        if (!isset($this->where_sql)) {
            $this->where_sql = [];
            $this->where_values = [];
        }
        $this->where_sql[] = "$field $operation ?";
        $this->where_values[] = $value;

        return $this;
    }

    public function limit($limit, $offset = 0) {
        $this->limit_sql = "$offset, $limit";
        return $this;
    }

    public function orderBy($field, $direction = 'ASC') {
        if (!isset($this->order_sql)) {
            $this->order_sql = [];
        }
        $this->order_sql[] = "$field $direction";
        return $this;
    }

    public function groupBy($field) {
        if (!isset($this->group_sql)) {
            $this->group_sql = [];
        }
        $this->group_sql[] = $field;
        return $this;
    }

    public function join($t1, $k1, $t2, $k2) {
        if (!isset($this->join_sql)) {
            $this->join_sql = [];
        }
        $this->join_sql[] = "JOIN $t2 ON $t1.$k1 = $t2.$k2";
        return $this;
    }

    public function __set($name, $value) {
        $this->field_values[$name] = $value;
        return $this;
    }

    public function __get($name) {
        return $this->field_values[$name];
    }

}
