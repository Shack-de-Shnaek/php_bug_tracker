<?php

use function PHPSTORM_META\type;

class Model {
    public $db;
    protected $table;
    public $columns = [];

    public function __construct(string $table, array $columns) {
        require_once '/xampp/htdocs/config/Database.php';
        $this->db = $database;
        $this->table = $table;
        $this->columns = $columns;
    }

    public function get_all() {
        try {
            $query = "SELECT * FROM $this->table";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch(PDOException $e) {
            return $e;
        }
    }

    public function get_limit_offset(int $limit, int $offset) {
        try {
            $query = "SELECT * FROM $this->table LIMIT $limit OFFSET $offset";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch(PDOException $e) {
            return $e;
        }
    }

    public function get_by_id(int $id) {
        try {
            $getId = $this->columns[0];
            $query = "SELECT * FROM $this->table WHERE $getId = $id";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch(PDOException $e) {
            return $e;
        }
    }

    public function get_by_column(string $column,  $value) {
        try {
            if(gettype($value) === 'string') $value = "\"" . $value . "\"";
            $query = "SELECT * FROM $this->table WHERE $column = $value";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch(PDOException $e) {
            return $e;
        }
    }

    public function get_id_by_column(string $column,  $value) {
        try {
            if(gettype($value) === 'string') $value = "\"" . $value . "\"";
            $query = "SELECT " . $this->columns[0] . " FROM $this->table WHERE $column = $value";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            print_r($row);
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_column_by_id(int $id, string $column) {
        try {
            $query = "SELECT $column FROM $this->table WHERE " . $this->columns[0] . " = $id";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            printf($raw);
            return $raw[0];
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function update_by_id(int $id, string $column,  $value) {
        try {
            $udId = $this->columns[0];
            if(gettype($value) === 'string') $value = "'" . $value . "'";
            $query = "UPDATE $this->table SET $column = $value WHERE $udId = $id";
            $this->db->beginTransaction();
            $this->db->exec($query);
            return $this->db->commit();
        } catch(PDOException $e) {
            return $e;
        }
    }
    
    public function delete_by_id(int $id) {
        try {
            $delId = $this->columns[0];
            $query = "DELETE FROM $this->table WHERE $delId = $id";
            $this->db->beginTransaction();
            $this->db->exec($query);
            return $this->db->commit();
        } catch(PDOException $e) {
            return $e;
        }
    }
}