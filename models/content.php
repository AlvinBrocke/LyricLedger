<?php 
require_once __DIR__ . '/../classes/DB.php';
class Content {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function uploadContent($data) {
        $sql = "INSERT INTO content (id, user_id, album_id, genre_id, title, file_path, duration, fingerprint_path, status) VALUES (UUID(), :user_id, :album_id, :genre_id, :title, :file_path, :duration, :fingerprint_path, :status)";
        return $this->db->insert($sql, $data);
    }

    public function getAllByUser($userId) {
        $sql = "SELECT * FROM content WHERE user_id = :user_id";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function getContentDetails($id) {
        $sql = "SELECT * FROM content WHERE id = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
}