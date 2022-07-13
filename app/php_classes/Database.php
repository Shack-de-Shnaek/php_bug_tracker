<?php
class Database {
    private $user;
    private $pass;
    private $host;
    private $port;
    public $db;

    public function __construct(string $user='root', string $pass='root', string $host='localhost', int $port=3306)  {
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        $this->port = $port;
        try {
            $this->db = new PDO("mysql:host=$this->host;port=$this->port;dbname=bug_tracker", $this->user, $this->pass);
            return $this->db;
        } catch(PDOException $e) {
            return $e;
            exit();
        }
    }

    public function db() {
        return $this->db;
    }
}