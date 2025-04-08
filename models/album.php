<?php

require_once __DIR__ . '/../classes/db.php';

class Album {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function createAlbum($data) {
        $sql = "INSERT INTO albums (id, user_id, title, description, cover_image, release_date) VALUES (UUID(), :user_id, :title, :description, :cover_image, :release_date)";
        return $this->db->insert($sql, $data);
    }
}