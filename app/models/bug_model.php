<?php
require_once '/xampp/htdocs/php_classes/Model.php';

class bugModel extends Model {
    public function __construct() {
        parent::__construct('Bugs', ['idBug', 'name', 'description', 'severity', 'status', 'projectId', 'submittedAt', 'fixedAt', 'submitterId', 'fixerId']);
    }

    public function create(string $name, string $description, string $severity, int $projectId, int $submitterId) {
        try {   
            $columns = '';
            foreach(array_slice($this->columns, 1, count($this->columns)-1, true) as $index => $column) {
                if($index < count(array_slice($this->columns, 1, count($this->columns)-1, true))) $columns = $columns . "$column, ";
                else $columns = $columns . $column;
            }
            $this->db->beginTransaction();
            $query = "INSERT INTO $this->table 
            (name, description, severity, status, projectId, submittedAt, submitterId) 
            VALUES 
            (\"$name\", \"$description\", \"$severity\", \"submitted\", $projectId, current_time(), $submitterId);";
            $this->db->exec($query);
            if($this->db->commit()) {
                $raw = $this->db->query("SELECT * FROM ".$this->table." WHERE name = \"$name\" AND description = \"$description\" AND projectId = $projectId", PDO::FETCH_ASSOC);
                $res = [];
                foreach($raw as $row) $res[]= $row;
                return $res;
            } else return false;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_submitter(int $bugId) {
        try {
            $query = "
                SELECT u.idUser, u.name, u.surname, u.email
                FROM users u
                JOIN bugs b
                ON u.idUser = b.submitterId AND b.idBug = $bugId;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_fixer(int $bugId) {
        try {
            $query = "
                SELECT u.idUser, u.name, u.surname, u.email
                FROM users u
                JOIN bugs b
                ON u.idUser = p.fixerId AND b.idBug = $bugId;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function fix(int $id ,int $fixerId) {
        try {   
            $query = "
                UPDATE bugs
                SET status = 'fixed', fixedAt = current_time(), fixerId = $fixerId
                WHERE idBug = $id;
            ";
            $this->db->beginTransaction();
            $this->db->exec($query);
            return $this->db->commit();
        } catch (PDOException $e) {
            return $e;
        }
    }
}