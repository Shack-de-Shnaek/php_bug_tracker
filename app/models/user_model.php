<?php
require_once "/xampp/htdocs/php_classes/Model.php";

class userModel extends Model {
    public function __construct() {
        parent::__construct('Users', ['idUser', 'isAdmin', 'name', 'surname', 'email', 'password']);
    }

    public function get_all() {
        try {
            $query = "
            SELECT idUser, name, surname, email 
            FROM $this->table";
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
            $query = "
            SELECT idUser, name, surname, email 
            FROM $this->table LIMIT $limit OFFSET $offset";
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
            $query = "
            SELECT idUser, name, surname, email 
            FROM $this->table WHERE $getId = $id";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch(PDOException $e) {
            return $e;
        }
    }

    public function get_by_column(string $column, $value) {
        try {
            if(gettype($value) === 'string') $value = "\"" . $value . "\"";
            $query = "
            SELECT idUser, name, surname, email 
            FROM $this->table WHERE $column = $value";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch(PDOException $e) {
            return $e;
        }
    }
    
    public function create(int $isAdmin, string $name, string $surname, string $email, string $password) {
        try {   
            $columns = '';
            foreach(array_slice($this->columns, 1, count($this->columns)-1, true) as $index => $column) {
                if($index < count(array_slice($this->columns, 1, count($this->columns)-1, true))) $columns = $columns . "$column, ";
                else $columns = $columns . $column;
            }
            $this->db->beginTransaction();
            $query = "
            INSERT INTO ".$this->table." ($columns)
            SELECT * FROM (SELECT $isAdmin AS isAdmin, \"$name\" AS name, \"$surname\" AS surname, \"$email\" AS email, \"$password\" AS password) AS new_value
            WHERE NOT EXISTS (
                SELECT email FROM ".$this->table." WHERE email = \"$email\"
            ) LIMIT 1;
            ";
            $this->db->exec($query);
            return $this->db->commit();
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_leaders() {
        try {
            $query = "
                SELECT u.idUser, u.name, u.surname, u.email
                FROM users u
                RIGHT JOIN projects p
                ON u.idUser = p.leaderId;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            print_r($raw);
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_projects(int $userId) {
        try {
            $query = "
                SELECT p.*
                FROM projects p
                JOIN user_project up
                ON up.projectId = p.idProject AND up.userId = $userId
                ORDER BY p.idProject ASC;
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch(PDOException $e) {
            return $e;
        }
    }

    public function get_posts(int $userId) {
        try {   
            $query = "
                SELECT ps.*
                FROM posts ps
                JOIN users u
                ON u.idUser = ps.authorId AND ps.authorId = $userId
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

    public function get_submitted_bugs(int $userId) {
        try {   
            $query = "
                SELECT b.*
                FROM bugs b
                JOIN users u
                ON u.idUser = b.submitterId AND u.idUser = $userId
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
    
    public function get_fixed_bugs(int $userId) {
        try {   
            $query = "
                SELECT b.*
                FROM bugs b
                JOIN users u
                ON u.idUser = b.fixerId AND u.idUser = $userId
                ORDER BY b.fixedAt DESC;
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
            $query2 = "DELETE FROM user_project WHERE userId = $id";
            $query3 = "UPDATE posts SET authorId = 0 WHERE authorId = $id";
            $query4 = "UPDATE bugs SET submitterId = 0 WHERE submitterId = $id";
            $query5 = "UPDATE bugs SET fixerId = 0 WHERE fixerId = $id";
            $query6 = "UPDATE projects SET leaderId = 0 WHERE authorId = $id";
            $this->db->beginTransaction();
            $this->db->exec($query2);
            $this->db->exec($query3);
            $this->db->exec($query4);
            $this->db->exec($query5);
            $this->db->exec($query6);
            $this->db->exec($query1);
            return $this->db->commit();
        } catch(PDOException $e) {
            return $e;
        }
    }

    public function verify_login(string $email, string $password) {
        try {
            $query = "
                SELECT *
                FROM users
                WHERE email = \"$email\" AND password = \"$password\";
            ";
            $raw = $this->db->query($query, PDO::FETCH_ASSOC);
            $result = [];
            foreach($raw as $row) $result[] = $row;
            return $result;
        } catch (PDOException $e) {
            return $e;
        }
    }
}