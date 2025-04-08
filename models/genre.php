<?php

require_once __DIR__ . '/../classes/DB.php';
class Genre {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function getAllGenres() {
        $sql = "SELECT * FROM genres";
        return $this->db->fetchAll($sql);
    }
}