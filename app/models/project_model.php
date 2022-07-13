<?php
require_once "/xampp/htdocs/php_classes/Model.php";

class projectModel extends Model{
    public function __construct() {
        parent::__construct('projects', ['idProject', 'name', 'description', 'progress', 'status', 'leaderId']);
    }

    public function create(string $name, string $description, int $leaderId) {
        try {   
            $columns = '';
            foreach(array_slice($this->columns, 1, count($this->columns)-1, true) as $index => $column) {
                if($index < count(array_slice($this->columns, 1, count($this->columns)-1, true))) $columns = $columns . "$column, ";
                else $columns = $columns . $column;
            }
            $this->db->beginTransaction();
            $query1 = "
            INSERT INTO ".$this->table." ($columns)
            SELECT * FROM (SELECT \"$name\" AS name, \"$description\" AS description , 0 AS progress, \"just started\" AS status, $leaderId AS leaderId) AS new_value
            WHERE NOT EXISTS (
                SELECT name, description FROM ".$this->table." WHERE name = \"$name\" AND description = \"$description\"
            ) LIMIT 1;
            ";
            $query2 = "
            INSERT INTO user_project (userId, projectId) VALUES ($leaderId, (SELECT " . $this->columns[0] . " FROM $this->table WHERE leaderId = $leaderId AND name = \"$name\" AND description = \"$description\"));
            ";

            $query1;
            $query2;

            $this->db->exec($query1);
            $this->db->exec($query2);

            if($this->db->commit()) {
                $query3 = "SELECT ".$this->columns[0]." from ".$this->table." WHERE name = \"$name\" AND description = \"$description\"";
                $raw = $this->db->query($query3, PDO::FETCH_ASSOC);
                $res = [];
                foreach($raw as $row) $res[]= $row;
                return $res;
            } else return false;
        } catch (PDOException $e) {
            print_r($e);
            return false;
        }
    }
    
    public function add_member(int $projectId, int $memberId) {
        try {
            $this->db->beginTransaction();
            $query = "
                INSERT INTO user_project 
                (userId, projectId) 
                values 
                ($memberId, $projectId);
            ";
            $this->db->exec($query);
            if($this->db->commit()) {
                $raw = $this->db->query("SELECT name, surname, email FROM users WHERE idUser = memberId", PDO::FETCH_ASSOC);
                $res = [];
                foreach($raw as $row) $res[]= $row;
                return $res;
            } else return false;
        } catch (PDOException $e) {
            print_r($e);
            return false;
        }
    }
    
    public function add_member_by_email(int $projectId, string $memberEmail) {
        try {
            $this->db->beginTransaction();
            $query = "
                INSERT INTO user_project 
                (userId, projectId) 
                values (
                (SELECT idUser FROM users WHERE email = \"$memberEmail\"),
                $projectId);
            ";
            $this->db->exec($query);
            if($this->db->commit()) {
                $raw = $this->db->query("SELECT name, surname, email FROM users WHERE email = \"$memberEmail\"", PDO::FETCH_ASSOC);
                $res = [];
                foreach($raw as $row) $res[]= $row;
                return $res;
            } else return false;
        } catch (PDOException $e) {
            print_r($e);
            return false;
        }
    }

    public function get_members(int $projectId) {
        try {
            $query = "
                SELECT u.idUser, u.name, u.surname, u.email
                FROM users u
                JOIN user_project up
                ON up.userId = u.idUser AND up.projectId = $projectId
                ORDER BY u.idUser DESC;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_member_by_id(int $projectId, int $memberId) {
        try {
            $query = "
                SELECT u.idUser, u.name, u.surname, u.email
                FROM users u
                JOIN user_project up
                ON up.userId = u.idUser AND up.projectId = $projectId AND u.idUser = $memberId;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function remove_member(int $projectId, int $memberId) {
        try {
            $query = "
                DELETE FROM user_project up WHERE up.projectId = $projectId AND up.userId = $memberId;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_posts(int $projectId) {
        try {
            $query = "
                SELECT ps.*
                FROM posts ps
                JOIN projects pj
                ON ps.projectId = pj.idProject and pj.idProject = $projectId
                ORDER BY ps.createdAt DESC;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_all_bugs(int $projectId) {
        try {
            $query = "
                SELECT b.*
                FROM bugs b
                JOIN projects pj
                ON b.projectId = pj.idProject and pj.idProject = $projectId
                ORDER BY b.submittedAt DESC;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_unfixed_bugs(int $projectId) {
        try {
            $query = "
                SELECT b.*
                FROM bugs b
                JOIN projects pj
                ON b.projectId = pj.idProject and pj.idProject = $projectId
                WHERE b.fixerId IS NULL
                ORDER BY b.submittedAt ASC;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }
    
    public function get_fixed_bugs(int $projectId) {
        try {
            $query = "
                SELECT b.*
                FROM bugs b
                JOIN projects pj
                ON b.projectId = pj.idProject and pj.idProject = $projectId
                WHERE b.fixerId IS NOT NULL
                ORDER BY b.submittedAt ASC;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function delete_by_id(int $id) {
        try {
            $delId = $this->columns[0];
            $query1 = "DELETE FROM $this->table WHERE $delId = $id";
            $query2 = "DELETE FROM user_project WHERE projectId = $id";
            $query3 = "DELETE FROM posts WHERE projectId = $id";
            $query4 = "DELETE FROM bugs WHERE projectId = $id";
            $this->db->beginTransaction();
            $this->db->exec($query2);
            $this->db->exec($query3);
            $this->db->exec($query4);
            $this->db->exec($query1);
            return $this->db->commit();
        } catch(PDOException $e) {
            return $e;
        }
    }
}