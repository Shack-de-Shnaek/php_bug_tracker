<?php
require_once '/xampp/htdocs/php_classes/Model.php';

class postModel extends Model {
    public function __construct() {
        parent::__construct('Posts', ['idPost', 'contents', 'title', 'createdAt', 'projectId', 'authorid']);
    }

    public function create(string $contents, string $title, int $projectId, int $authorId) {
        try {   
            $columns = '';
            foreach(array_slice($this->columns, 1, count($this->columns)-1, true) as $index => $column) {
                if($index < count(array_slice($this->columns, 1, count($this->columns)-1, true))) $columns = $columns . "$column, ";
                else $columns = $columns . $column;
            }

            $query = "INSERT INTO $this->table ($columns) VALUES (\"$contents\", \"$title\", current_time(), $projectId, $authorId);";
            $this->db->beginTransaction();
            $this->db->exec($query);
            if($this->db->commit()) {
                $raw = $this->db->query("SELECT * FROM ".$this->table." WHERE title = \"$title\" AND contents = \"$contents\" AND projectId = $projectId", PDO::FETCH_ASSOC);
                $res = [];
                foreach($raw as $row) $res[]= $row;
                return $res;
            } else return false;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_author(int $postId) {
        try {
            $query = "
                SELECT u.idUser, u.name, u.surname, u.email
                FROM users u
                JOIN posts p
                ON u.idUser = p.authorId AND p.idPost = $postId;
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