<?php
require_once __DIR__ . '/../classes/db.php';

class Violation {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function reportViolation($data) {
        $sql = "INSERT INTO violations (id, content_id, detected_url, similarity_score, reported_by, status) VALUES (UUID(), :content_id, :detected_url, :similarity_score, :reported_by, :status)";
        return $this->db->insert($sql, $data);
    }

    public function getViolationsByContent($contentId) {
        $sql = "SELECT * FROM violations WHERE content_id = :content_id";
        return $this->db->fetchAll($sql, ['content_id' => $contentId]);
    }
}